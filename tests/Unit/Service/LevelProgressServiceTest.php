<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use App\Repository\TestResultRepository;
use App\Repository\TrainingLevelRepository;
use App\Repository\UserLevelProgressRepository;
use App\Repository\UserTrainingLogRepository;
use App\Service\AchievementUnlockService;
use App\Service\LevelProgressService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

final class LevelProgressServiceTest extends TestCase
{
    private LevelProgressService $service;

    /** @var EntityManagerInterface&Stub */
    private EntityManagerInterface $entityManager;

    /** @var UserLevelProgressRepository&MockObject */
    private UserLevelProgressRepository $progressRepository;

    /** @var UserTrainingLogRepository&MockObject */
    private UserTrainingLogRepository $trainingLogRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createStub(EntityManagerInterface::class);
        $this->progressRepository = $this->createMock(UserLevelProgressRepository::class);
        $this->trainingLogRepository = $this->createMock(UserTrainingLogRepository::class);

        $this->service = new LevelProgressService(
            $this->entityManager,
            $this->progressRepository,
            $this->createStub(TrainingLevelRepository::class),
            $this->createStub(AchievementUnlockService::class),
            $this->trainingLogRepository,
            $this->createStub(TestResultRepository::class),
        );
    }

    public function testAbandonActiveProgramMarksOpenSessionsOfThatProgram(): void
    {
        $user = new User();
        $program = new TrainingProgram();
        $progress = $this->createProgress($user, $program);

        $this->progressRepository->expects(self::once())
            ->method('findByUserAndProgram')
            ->with($user, $program)
            ->willReturn([$progress]);

        $this->trainingLogRepository->expects(self::once())
            ->method('abandonOpenSessionsForProgram')
            ->with($user, $program)
            ->willReturn(1);

        $this->service->abandonActiveProgram($user, $program);

        self::assertSame(UserLevelProgress::STATUS_ABANDONED, $progress->getStatus());
        self::assertNotNull($progress->getAbandonedAt());
    }

    public function testAbandonAllActiveProgramsMarksSessionsOfEachProgram(): void
    {
        $user = new User();
        $programA = new TrainingProgram();
        $programB = new TrainingProgram();
        $progressA = $this->createProgress($user, $programA);
        $progressB = $this->createProgress($user, $programB);

        $this->progressRepository->expects(self::once())
            ->method('findAllActiveByUser')
            ->with($user)
            ->willReturn([$progressA, $progressB]);

        $this->trainingLogRepository->expects(self::exactly(2))
            ->method('abandonOpenSessionsForProgram')
            ->willReturn(0);

        $this->service->abandonActiveProgram($user);

        self::assertSame(UserLevelProgress::STATUS_ABANDONED, $progressA->getStatus());
        self::assertSame(UserLevelProgress::STATUS_ABANDONED, $progressB->getStatus());
    }

    public function testAbandonAllActiveProgramsThrowsWhenNoneActive(): void
    {
        $this->progressRepository->expects(self::once())
            ->method('findAllActiveByUser')
            ->willReturn([]);

        $this->trainingLogRepository->expects(self::never())
            ->method('abandonOpenSessionsForProgram');

        $this->expectException(\InvalidArgumentException::class);

        $this->service->abandonActiveProgram(new User());
    }

    private function createProgress(User $user, TrainingProgram $program): UserLevelProgress
    {
        $level = new TrainingLevel();
        $level->setProgram($program);

        $progress = new UserLevelProgress();
        $progress->setUser($user);
        $progress->setTrainingLevel($level);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);

        return $progress;
    }
}
