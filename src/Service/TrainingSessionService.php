<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Training;
use App\Entity\TrainingLevel;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use App\Entity\UserTrainingLog;
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
    ) {
    }

    /**
     * Inicia una sesión de entrenamiento. Si el entrenamiento pertenece a un nivel,
     * se asegura de que el usuario tenga un progreso activo para dicho nivel.
     */
    public function startSession(User $user, Training $training): UserTrainingLog
    {
        $level = $training->getTrainingLevel();

        if (null !== $level) {
            $this->ensureLevelProgressIsActive($user, $level);
        }

        $log = new UserTrainingLog();
        $log->setUser($user);
        $log->setTraining($training);
        $log->setSource(UserTrainingLog::SOURCE_PROGRAM);

        $this->entityManager->persist($log);
        $this->entityManager->flush();

        return $log;
    }

    /**
     * Completa la sesión iniciada para el entrenamiento indicado, actualiza las
     * estadísticas del usuario y avanza de semana si se completó la fase actual.
     */
    public function completeSession(User $user, Training $training, ?int $durationSeconds, int $xpEarned): UserTrainingLog
    {
        $log = $this->trainingLogRepository->createQueryBuilder('log')
            ->where('log.user = :user')
            ->andWhere('log.training = :training')
            ->andWhere('log.completedAt IS NULL')
            ->setParameter('user', $user)
            ->setParameter('training', $training)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$log instanceof UserTrainingLog) {
            throw new \InvalidArgumentException('No hay una sesión activa para este entrenamiento.');
        }

        $now = new \DateTimeImmutable();
        $log->setCompletedAt($now);
        $log->setDurationSeconds($durationSeconds);
        $log->setXpEarned($xpEarned);

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
}
