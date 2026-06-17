<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\TestResult;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use App\Repository\TrainingLevelRepository;
use App\Repository\UserLevelProgressRepository;
use Doctrine\ORM\EntityManagerInterface;

class LevelProgressService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserLevelProgressRepository $progressRepository,
        private readonly TrainingLevelRepository $levelRepository,
    ) {
    }

    /**
     * Inicializa el progreso de un usuario para un programa.
     * Desbloquea el nivel 1 y deja el resto bloqueados.
     */
    public function initializeProgress(User $user, array $levels): void
    {
        foreach ($levels as $level) {
            $progress = new UserLevelProgress();
            $progress->setUser($user);
            $progress->setTrainingLevel($level);
            $progress->setStatus(UserLevelProgress::STATUS_LOCKED);
            $progress->setCyclesCompleted(0);
            $progress->setCurrentWeek(0);

            // El primer nivel queda in_progress, el resto locked
            if (1 === $level->getLevelNumber()) {
                $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
            }

            $this->entityManager->persist($progress);
        }

        $this->entityManager->flush();
    }

    /**
     * Inicia o reinicia el progreso de un programa sin afectar a otros programas activos.
     * Si el usuario ya tiene un progreso activo en este programa, no se realiza ningún cambio.
     * Si tiene progresos previos no activos, se eliminan para reiniciar de cero.
     */
    public function switchProgram(User $user, TrainingProgram $program): void
    {
        $existingProgresses = $this->progressRepository->findByUserAndProgram($user, $program);

        // Si ya existe un progreso activo en este programa, no hacemos nada.
        $hasActive = false;
        foreach ($existingProgresses as $progress) {
            if (\in_array($progress->getStatus(), [UserLevelProgress::STATUS_IN_PROGRESS, UserLevelProgress::STATUS_REPEAT], true)) {
                $hasActive = true;
            }
        }

        if ($hasActive) {
            return;
        }

        // Eliminar progresos previos del programa objetivo para reiniciar de cero
        foreach ($existingProgresses as $progress) {
            $this->entityManager->remove($progress);
        }

        $this->entityManager->flush();

        // Inicializar progreso nuevo
        $levels = $this->levelRepository->findByProgramOrdered($program->getId());
        $this->initializeProgress($user, $levels);
    }

    /**
     * Abandona uno o todos los programas activos del usuario.
     * Si se indica un programa, solo se abandona ese. En caso contrario se abandonan todos los activos.
     */
    public function abandonActiveProgram(User $user, ?TrainingProgram $program = null): void
    {
        if (null !== $program) {
            $programProgresses = $this->progressRepository->findByUserAndProgram($user, $program);

            foreach ($programProgresses as $progress) {
                $progress->abandon();
            }

            $this->entityManager->flush();

            return;
        }

        $activeProgresses = $this->progressRepository->findAllActiveByUser($user);

        if (empty($activeProgresses)) {
            throw new \InvalidArgumentException('No tienes ningún programa activo para abandonar.');
        }

        foreach ($activeProgresses as $progress) {
            $progress->abandon();
        }

        $this->entityManager->flush();
    }

    /**
     * Registra los resultados de un test y evalúa si el usuario pasa de nivel.
     *
     * @param array<int, array{name: string, value: int|float}>   $results
     * @param array<int, array{name: string, minimum: int|float}> $requirements
     */
    public function submitTestResults(
        UserLevelProgress $progress,
        array $results,
        array $requirements,
        ?string $notes = null
    ): TestResult {
        $testResult = new TestResult();
        $testResult->setUserLevelProgress($progress);
        $testResult->setLevelNumber($progress->getTrainingLevel()->getLevelNumber());
        $testResult->setCycleNumber($progress->getCyclesCompleted());
        $testResult->setResults($results);
        $testResult->setNotes($notes);

        $testResult->evaluate($requirements);

        $this->entityManager->persist($testResult);

        if ($testResult->isOverallPassed()) {
            $this->handleLevelPassed($progress);
        } else {
            $this->handleLevelRepeat($progress);
        }

        $this->entityManager->flush();

        return $testResult;
    }

    /**
     * Avanza el progreso del usuario al siguiente nivel.
     */
    private function handleLevelPassed(UserLevelProgress $progress): void
    {
        $progress->setStatus(UserLevelProgress::STATUS_COMPLETED);
        $progress->setCompletedAt(new \DateTimeImmutable());

        // Desbloquear siguiente nivel
        $currentLevel = $progress->getTrainingLevel();
        $nextLevel = $this->levelRepository->findOneBy([
            'program' => $currentLevel->getProgram(),
            'levelNumber' => $currentLevel->getLevelNumber() + 1,
        ]);

        if (null !== $nextLevel) {
            $nextProgress = $this->progressRepository->findByUserAndLevel($progress->getUser(), $nextLevel);

            if (null === $nextProgress) {
                $nextProgress = new UserLevelProgress();
                $nextProgress->setUser($progress->getUser());
                $nextProgress->setTrainingLevel($nextLevel);
                $this->entityManager->persist($nextProgress);
            }

            $nextProgress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
            $nextProgress->setCyclesCompleted(0);
            $nextProgress->setCurrentWeek(0);
        }
    }

    /**
     * Marca el nivel para repetir (incrementa ciclo y reinicia semana).
     */
    private function handleLevelRepeat(UserLevelProgress $progress): void
    {
        $progress->incrementCycle();
        $progress->setStatus(UserLevelProgress::STATUS_REPEAT);
    }

    /**
     * Obtiene el progreso activo de un usuario.
     */
    public function getActiveProgress(User $user): ?UserLevelProgress
    {
        return $this->progressRepository->findActiveByUser($user);
    }

    /**
     * Avanza la semana actual del progreso activo.
     */
    public function advanceWeek(UserLevelProgress $progress): void
    {
        $progress->advanceWeek();
        $this->entityManager->flush();
    }
}
