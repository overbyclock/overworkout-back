<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Training;
use App\Entity\TrainingLevel;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use App\Entity\UserTrainingLog;
use App\Entity\UserTrainingSessionStep;
use App\Repository\TrainingLevelRepository;
use App\Repository\UserLevelProgressRepository;
use App\Repository\UserTrainingLogRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class TrainingSessionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserLevelProgressRepository $progressRepository,
        private UserTrainingLogRepository $trainingLogRepository,
        private TrainingLevelRepository $levelRepository,
        private LevelProgressService $levelProgressService,
        private TrainingSessionExpander $expander,
    ) {
    }

    /**
     * Inicia una sesión de entrenamiento generando el snapshot de pasos.
     * Si ya existe una sesión activa para el entrenamiento, la devuelve sin
     * crear una nueva (idempotente).
     *
     * @return array{log: UserTrainingLog, created: bool}
     */
    public function startSession(User $user, Training $training): array
    {
        $existing = $this->trainingLogRepository->findActiveByUserAndTraining($user, $training);

        if (null !== $existing) {
            return ['log' => $existing, 'created' => false];
        }

        $level = $training->getTrainingLevel();

        if (null !== $level) {
            $this->ensureLevelProgressIsActive($user, $level);
        }

        $log = new UserTrainingLog();
        $log->setUser($user);
        $log->setTraining($training);
        $log->setSource(null !== $level ? UserTrainingLog::SOURCE_PROGRAM : UserTrainingLog::SOURCE_FREE);

        // Puede lanzar InvalidArgumentException si el entrenamiento no tiene contenido.
        $this->expander->expand($training, $log);

        $this->entityManager->persist($log);
        $this->entityManager->flush();

        return ['log' => $log, 'created' => true];
    }

    /**
     * Completa la sesión iniciada para el entrenamiento indicado, actualiza las
     * estadísticas del usuario y avanza de semana si se completó la fase actual.
     */
    public function completeSession(User $user, Training $training, ?int $durationSeconds, int $xpEarned): UserTrainingLog
    {
        $log = $this->trainingLogRepository->findActiveByUserAndTraining($user, $training);

        if (!$log instanceof UserTrainingLog) {
            throw new \InvalidArgumentException('No hay una sesión activa para este entrenamiento.');
        }

        $now = new \DateTimeImmutable();
        $log->setCompletedAt($now);
        $log->setDurationSeconds($durationSeconds);
        $log->setXpEarned($xpEarned);

        // Los pasos que sigan pendientes se marcan como omitidos al cerrar la sesión.
        foreach ($log->getSessionSteps() as $step) {
            if ($step->isPending()) {
                $step->skip();
            }
        }

        $user->addXp($xpEarned);
        $user->incrementWorkouts();
        $user->setLastWorkoutAt($now);

        // Asegurar que el log completado es visible antes de evaluar el avance de semana.
        $this->entityManager->flush();
        $this->advanceWeekIfPhaseCompleted($user, $training);

        return $log;
    }

    /**
     * Devuelve la siguiente sesión pendiente del usuario: la sesión iniciada que
     * aún no ha completado, o el siguiente entrenamiento pendiente de su semana actual.
     *
     * @return array<string, mixed>|null
     */
    public function getNextSessionForUser(User $user): ?array
    {
        $activeLog = $this->trainingLogRepository->findStartedButNotCompletedByUser($user);

        if (null !== $activeLog && null !== $activeLog->getTraining()) {
            return [
                'type' => 'continue',
                'logId' => $activeLog->getId(),
                'training' => [
                    'id' => $activeLog->getTraining()->getId(),
                    'name' => $activeLog->getTraining()->getName(),
                ],
                'startedAt' => $activeLog->getStartedAt()?->format(\DateTimeInterface::ATOM),
            ];
        }

        $progress = $this->levelProgressService->getActiveProgress($user);

        if (null === $progress || null === $progress->getTrainingLevel()) {
            return null;
        }

        $level = $progress->getTrainingLevel();
        $weekNumber = $progress->getCurrentWeek();
        $trainings = $this->levelRepository->findTrainingsForWeek($level, $weekNumber);

        foreach ($trainings as $training) {
            if (!$this->isTrainingCompletedByUser($user, $training)) {
                return [
                    'type' => 'next',
                    'training' => [
                        'id' => $training->getId(),
                        'name' => $training->getName(),
                    ],
                    'levelNumber' => $level->getLevelNumber(),
                    'weekNumber' => $weekNumber,
                ];
            }
        }

        return null;
    }

    /**
     * Aplica una acción (complete|skip) sobre un paso de la sesión y avanza
     * el puntero de posición al siguiente paso pendiente.
     *
     * @throws \DomainException          si la sesión ya está completada
     * @throws \InvalidArgumentException si se pide scope exercise sobre un paso de descanso
     */
    public function applyStepAction(UserTrainingLog $log, UserTrainingSessionStep $step, string $action, string $scope): void
    {
        $this->assertSessionIsActive($log);

        if ('complete' === $action) {
            if ($step->isPending()) {
                $step->complete();
            }
        } elseif ('exercise' === $scope) {
            $this->skipExerciseSteps($log, $step);
        } elseif ($step->isPending()) {
            $step->skip();
        }

        $this->advanceCurrentPosition($log);
        $this->entityManager->flush();
    }

    /**
     * Retrocede al paso anterior de la sesión y lo reabre (vuelve a pendiente).
     *
     * @throws \DomainException          si la sesión ya está completada
     * @throws \InvalidArgumentException si no hay paso anterior
     */
    public function goBack(UserTrainingLog $log): void
    {
        $this->assertSessionIsActive($log);

        $previous = null;
        foreach ($log->getSessionSteps() as $step) {
            if ($step->getPosition() < $log->getCurrentPosition()) {
                $previous = $step;
            }
        }

        if (null === $previous) {
            throw new \InvalidArgumentException('No hay paso anterior.');
        }

        $previous->reopen();
        $log->setCurrentPosition($previous->getPosition());
        $this->entityManager->flush();
    }

    /**
     * Asegura que el usuario tenga un progreso activo para el nivel indicado.
     */
    private function ensureLevelProgressIsActive(User $user, TrainingLevel $level): void
    {
        $progress = $this->progressRepository->findByUserAndLevel($user, $level);

        if (null === $progress) {
            $progress = new UserLevelProgress();
            $progress->setUser($user);
            $progress->setTrainingLevel($level);
            $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
            $progress->setCyclesCompleted(0);
            $progress->setCurrentWeek(0);
            $this->entityManager->persist($progress);

            return;
        }

        if (UserLevelProgress::STATUS_LOCKED === $progress->getStatus()) {
            $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        }
    }

    /**
     * Si el usuario ha completado todos los entrenamientos de la semana del nivel
     * al que pertenece el entrenamiento, avanza la semana del progreso activo.
     */
    private function advanceWeekIfPhaseCompleted(User $user, Training $training): void
    {
        $level = $training->getTrainingLevel();
        $weekNumber = $training->getWeekNumber();

        if (null === $level || null === $weekNumber) {
            return;
        }

        $progress = $this->progressRepository->findByUserAndLevel($user, $level);

        if (null === $progress) {
            return;
        }

        $weekTrainings = $this->levelRepository->findTrainingsForWeek($level, $weekNumber);
        $completedLogs = $this->trainingLogRepository->findCompletedByUserAndLevelAndWeek($user, $level, $weekNumber);

        if (\count($weekTrainings) > 0 && \count($completedLogs) >= \count($weekTrainings)) {
            $this->levelProgressService->advanceWeek($progress);
        }
    }

    /**
     * Indica si el usuario tiene al menos un log completado para el entrenamiento.
     */
    private function isTrainingCompletedByUser(User $user, Training $training): bool
    {
        $count = $this->trainingLogRepository->createQueryBuilder('log')
            ->select('COUNT(log.id)')
            ->where('log.user = :user')
            ->andWhere('log.training = :training')
            ->andWhere('log.completedAt IS NOT NULL')
            ->setParameter('user', $user)
            ->setParameter('training', $training)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count > 0;
    }

    /**
     * Marca como omitidos todos los pasos pendientes del mismo ejercicio, vuelta
     * y bloque (incluidos los descansos intermedios), desde el paso indicado.
     */
    private function skipExerciseSteps(UserTrainingLog $log, UserTrainingSessionStep $fromStep): void
    {
        $payload = $fromStep->getPayload();

        if (UserTrainingSessionStep::TYPE_EXERCISE !== $fromStep->getType()) {
            throw new \InvalidArgumentException('Solo se puede saltar un ejercicio completo desde un paso de ejercicio.');
        }

        $exerciseId = $payload['exerciseId'] ?? null;
        $lap = $payload['lapNumber'] ?? null;
        $block = $payload['blockNumber'] ?? null;

        $lastPosition = $fromStep->getPosition();
        foreach ($log->getSessionSteps() as $candidate) {
            $candidatePayload = $candidate->getPayload();
            if (
                $candidate->getPosition() >= $fromStep->getPosition()
                && ($candidatePayload['exerciseId'] ?? null) === $exerciseId
                && ($candidatePayload['lapNumber'] ?? null) === $lap
                && ($candidatePayload['blockNumber'] ?? null) === $block
            ) {
                $lastPosition = $candidate->getPosition();
            }
        }

        foreach ($log->getSessionSteps() as $candidate) {
            if (
                $candidate->getPosition() >= $fromStep->getPosition()
                && $candidate->getPosition() <= $lastPosition
                && $candidate->isPending()
            ) {
                $candidate->skip();
            }
        }
    }

    private function advanceCurrentPosition(UserTrainingLog $log): void
    {
        foreach ($log->getSessionSteps() as $step) {
            if ($step->isPending()) {
                $log->setCurrentPosition($step->getPosition());

                return;
            }
        }

        $lastStep = $log->getSessionSteps()->last();
        $log->setCurrentPosition(false !== $lastStep ? $lastStep->getPosition() + 1 : 0);
    }

    private function assertSessionIsActive(UserTrainingLog $log): void
    {
        if (null !== $log->getCompletedAt()) {
            throw new \DomainException('La sesión ya está completada.');
        }
    }
}
