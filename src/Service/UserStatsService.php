<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Training;
use App\Entity\User;
use App\Entity\UserTrainingLog;
use App\Repository\UserTrainingLogRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserStatsService
{
    private const XP_PER_TRAINING = 50;
    private const STREAK_LOOKUP_DAYS = 365;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserTrainingLogRepository $trainingLogRepository,
    ) {
    }

    /**
     * Registra la finalización de un entrenamiento y actualiza las estadísticas del usuario.
     *
     * @param array<string, mixed> $context
     */
    public function recordTrainingCompletion(User $user, ?Training $training = null, array $context = []): UserTrainingLog
    {
        $log = new UserTrainingLog();
        $log->setUser($user);
        $log->setTraining($training);
        $log->setSource($context['source'] ?? UserTrainingLog::SOURCE_PROGRAM);
        $log->setXpEarned($context['xp'] ?? self::XP_PER_TRAINING);

        if (isset($context['durationSeconds']) && \is_int($context['durationSeconds'])) {
            $log->setDurationSeconds($context['durationSeconds']);
        }

        if (isset($context['completedAt']) && $context['completedAt'] instanceof \DateTimeImmutable) {
            $log->setCompletedAt($context['completedAt']);
        }

        $user->addUserTrainingLog($log);
        $user->addXp($log->getXpEarned());
        $user->incrementWorkouts();
        $user->setLastWorkoutAt(new \DateTimeImmutable());

        $this->entityManager->persist($log);
        $this->recalculateStreak($user);

        $this->entityManager->flush();

        return $log;
    }

    /**
     * Recalcula y guarda la racha del usuario a partir de sus logs recientes.
     */
    public function recalculateStreak(User $user): void
    {
        $streak = $this->calculateStreak($user);
        $user->setStreakDays($streak);
    }

    /**
     * Calcula la racha actual: días consecutivos con al menos un entrenamiento,
     * contando hacia atrás desde hoy o desde ayer si hoy aún no se ha entrenado.
     */
    public function calculateStreak(User $user): int
    {
        $since = (new \DateTimeImmutable())->modify(\sprintf('-%d days midnight', self::STREAK_LOOKUP_DAYS));
        $completedDates = $this->trainingLogRepository->findCompletedDatesByUser($user, $since);

        if (empty($completedDates)) {
            return 0;
        }

        $completedDates = array_flip($completedDates);
        $today = new \DateTimeImmutable('today');
        $yesterday = $today->modify('-1 day');

        $hasToday = isset($completedDates[$today->format('Y-m-d')]);
        $hasYesterday = isset($completedDates[$yesterday->format('Y-m-d')]);

        if (!$hasToday && !$hasYesterday) {
            return 0;
        }

        $streak = 0;
        $current = $hasToday ? $today : $yesterday;

        while (isset($completedDates[$current->format('Y-m-d')])) {
            ++$streak;
            $current = $current->modify('-1 day');
        }

        return $streak;
    }

    /**
     * Devuelve el número de entrenamientos completados desde el inicio de la semana actual (lunes).
     */
    public function getWeeklyWorkouts(User $user): int
    {
        $weekStart = new \DateTimeImmutable('monday this week midnight');
        $weekEnd = (new \DateTimeImmutable('sunday this week midnight'))->modify('+1 day -1 second');

        return $this->trainingLogRepository->countCompletedInDateRange($user, $weekStart, $weekEnd);
    }

    /**
     * Devuelve las estadísticas del dashboard para el usuario.
     *
     * @return array<string, mixed>
     */
    public function getDashboardStats(User $user): array
    {
        $this->recalculateStreak($user);

        return [
            'streakDays' => $user->getStreakDays(),
            'weeklyWorkouts' => $this->getWeeklyWorkouts($user),
            'xp' => $user->getXp(),
            'totalWorkouts' => $user->getTotalWorkouts(),
            'athleteLevel' => $user->getAthleteLevel(),
        ];
    }
}
