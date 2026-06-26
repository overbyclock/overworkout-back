<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Training;
use App\Entity\User;
use App\Entity\UserTrainingLog;
use App\Repository\UserTrainingLogRepository;
use App\Service\UserStatsService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserStatsServiceTest extends TestCase
{
    private UserStatsService $service;

    /** @var EntityManagerInterface&MockObject */
    private EntityManagerInterface $entityManager;

    /** @var UserTrainingLogRepository&MockObject */
    private UserTrainingLogRepository $trainingLogRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->trainingLogRepository = $this->createMock(UserTrainingLogRepository::class);

        $this->service = new UserStatsService(
            $this->entityManager,
            $this->trainingLogRepository,
        );
    }

    public function testRecordTrainingCompletionCreatesLogAndUpdatesUser(): void
    {
        $user = new User();
        $training = new Training();

        $this->entityManager->expects(self::once())
            ->method('persist')
            ->with(self::isInstanceOf(UserTrainingLog::class));

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->trainingLogRepository->expects(self::once())
            ->method('findCompletedDatesByUser')
            ->willReturn([]);

        $log = $this->service->recordTrainingCompletion($user, $training);

        self::assertSame($user, $log->getUser());
        self::assertSame($training, $log->getTraining());
        self::assertSame(UserTrainingLog::SOURCE_PROGRAM, $log->getSource());
        self::assertSame(50, $log->getXpEarned());
        self::assertSame(50, $user->getXp());
        self::assertSame(1, $user->getTotalWorkouts());
        self::assertNotNull($user->getLastWorkoutAt());
    }

    public function testRecordTrainingCompletionWithCustomXpAndDuration(): void
    {
        $user = new User();
        $training = new Training();
        $completedAt = new \DateTimeImmutable('2026-06-25 10:00:00');

        $this->entityManager->expects(self::once())
            ->method('persist');

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->trainingLogRepository->expects(self::once())
            ->method('findCompletedDatesByUser')
            ->willReturn([]);

        $log = $this->service->recordTrainingCompletion($user, $training, [
            'xp' => 100,
            'durationSeconds' => 1200,
            'source' => UserTrainingLog::SOURCE_FREE,
            'completedAt' => $completedAt,
        ]);

        self::assertSame(100, $log->getXpEarned());
        self::assertSame(1200, $log->getDurationSeconds());
        self::assertSame(UserTrainingLog::SOURCE_FREE, $log->getSource());
        self::assertSame($completedAt, $log->getCompletedAt());
        self::assertSame(100, $user->getXp());
    }

    public function testCalculateStreakReturnsZeroWhenNoWorkouts(): void
    {
        $user = new User();

        $this->trainingLogRepository->expects(self::once())
            ->method('findCompletedDatesByUser')
            ->willReturn([]);

        self::assertSame(0, $this->service->calculateStreak($user));
    }

    public function testCalculateStreakCountsToday(): void
    {
        $user = new User();
        $today = new \DateTimeImmutable('today');

        $this->trainingLogRepository->expects(self::once())
            ->method('findCompletedDatesByUser')
            ->willReturn([
                $today->format('Y-m-d'),
                $today->modify('-1 day')->format('Y-m-d'),
                $today->modify('-2 day')->format('Y-m-d'),
            ]);

        self::assertSame(3, $this->service->calculateStreak($user));
    }

    public function testCalculateStreakCountsFromYesterdayWhenTodayMissing(): void
    {
        $user = new User();
        $today = new \DateTimeImmutable('today');

        $this->trainingLogRepository->expects(self::once())
            ->method('findCompletedDatesByUser')
            ->willReturn([
                $today->modify('-1 day')->format('Y-m-d'),
                $today->modify('-2 day')->format('Y-m-d'),
            ]);

        self::assertSame(2, $this->service->calculateStreak($user));
    }

    public function testCalculateStreakStopsAtGap(): void
    {
        $user = new User();
        $today = new \DateTimeImmutable('today');

        $this->trainingLogRepository->expects(self::once())
            ->method('findCompletedDatesByUser')
            ->willReturn([
                $today->format('Y-m-d'),
                $today->modify('-1 day')->format('Y-m-d'),
                $today->modify('-3 day')->format('Y-m-d'),
            ]);

        self::assertSame(2, $this->service->calculateStreak($user));
    }

    public function testCalculateStreakReturnsZeroWhenGapBeforeYesterday(): void
    {
        $user = new User();
        $today = new \DateTimeImmutable('today');

        $this->trainingLogRepository->expects(self::once())
            ->method('findCompletedDatesByUser')
            ->willReturn([
                $today->modify('-2 day')->format('Y-m-d'),
                $today->modify('-3 day')->format('Y-m-d'),
            ]);

        self::assertSame(0, $this->service->calculateStreak($user));
    }

    public function testGetWeeklyWorkouts(): void
    {
        $user = new User();

        $this->trainingLogRepository->expects(self::once())
            ->method('countCompletedInDateRange')
            ->willReturnCallback(static function (User $u, \DateTimeInterface $start, \DateTimeInterface $end) use ($user) {
                self::assertSame($user, $u);
                self::assertSame('Monday', $start->format('l'));
                self::assertSame('Sunday', $end->format('l'));

                return 3;
            });

        self::assertSame(3, $this->service->getWeeklyWorkouts($user));
    }

    public function testGetDashboardStats(): void
    {
        $user = new User();
        $user->setXp(150);
        $user->setTotalWorkouts(5);
        $user->setAthleteLevel(2);

        $this->trainingLogRepository->expects(self::once())
            ->method('findCompletedDatesByUser')
            ->willReturn([]);

        $this->trainingLogRepository->expects(self::once())
            ->method('countCompletedInDateRange')
            ->willReturn(2);

        $stats = $this->service->getDashboardStats($user);

        self::assertSame([
            'streakDays' => 0,
            'weeklyWorkouts' => 2,
            'xp' => 150,
            'totalWorkouts' => 5,
            'athleteLevel' => 2,
        ], $stats);
    }
}
