<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\TestResult;
use App\Entity\TrainingLevel;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use App\Repository\TestResultRepository;
use App\Repository\TrainingLevelRepository;
use App\Repository\UserLevelProgressRepository;
use Doctrine\ORM\EntityManagerInterface;

class LevelProgressService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserLevelProgressRepository $progressRepository,
        private readonly TestResultRepository $testResultRepository,
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
            if ($level->getLevelNumber() === 1) {
                $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
            }

            $this->entityManager->persist($progress);
        }

        $this->entityManager->flush();
    }

    /**
     * Registra los resultados de un test y evalúa si el usuario pasa de nivel.
     *
     * @param array<int, array{name: string, value: int|float}> $results
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

        if ($nextLevel !== null) {
            $nextProgress = $this->progressRepository->findByUserAndLevel($progress->getUser(), $nextLevel);

            if ($nextProgress === null) {
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
