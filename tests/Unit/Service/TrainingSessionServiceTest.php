<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingProgram;
use App\Entity\TrainingRound;
use App\Entity\User;
use App\Entity\UserTrainingLog;
use App\Entity\UserTrainingSessionStep;
use App\Repository\TrainingLevelRepository;
use App\Repository\UserLevelProgressRepository;
use App\Repository\UserTrainingLogRepository;
use App\Service\LevelProgressService;
use App\Service\TrainingSessionExpander;
use App\Service\TrainingSessionService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TrainingSessionServiceTest extends TestCase
{
    private TrainingSessionService $service;

    /** @var EntityManagerInterface&MockObject */
    private EntityManagerInterface $entityManager;

    /** @var UserTrainingLogRepository&MockObject */
    private UserTrainingLogRepository $trainingLogRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->trainingLogRepository = $this->createMock(UserTrainingLogRepository::class);

        $this->service = new TrainingSessionService(
            $this->entityManager,
            $this->createMock(UserLevelProgressRepository::class),
            $this->trainingLogRepository,
            $this->createMock(TrainingLevelRepository::class),
            $this->createMock(LevelProgressService::class),
            new TrainingSessionExpander(),
        );
    }

    public function testStartSessionCreatesLogWithInProgressStatus(): void
    {
        // Un log abandoned ya no lo devuelve el repositorio como activo:
        // la nueva sesión debe nacer siempre como in_progress.
        $user = new User();
        $training = $this->createMinimalTraining();

        $this->trainingLogRepository->expects(self::once())
            ->method('findActiveByUserAndTraining')
            ->with($user, $training)
            ->willReturn(null);

        $result = $this->service->startSession($user, $training);

        self::assertTrue($result['created']);
        self::assertSame(UserTrainingLog::STATUS_IN_PROGRESS, $result['log']->getStatus());
        self::assertNull($result['log']->getCompletedAt());
    }

    public function testStartSessionReusesActiveLogWhenRepositoryFindsOne(): void
    {
        $user = new User();
        $training = $this->createMinimalTraining();
        $existing = new UserTrainingLog();
        $existing->setUser($user);
        $existing->setTraining($training);

        $this->trainingLogRepository->expects(self::once())
            ->method('findActiveByUserAndTraining')
            ->willReturn($existing);

        $this->entityManager->expects(self::never())->method('persist');

        $result = $this->service->startSession($user, $training);

        self::assertFalse($result['created']);
        self::assertSame($existing, $result['log']);
    }

    public function testCompleteSessionMarksLogAsCompleted(): void
    {
        $user = new User();
        $training = $this->createMinimalTraining();
        $log = new UserTrainingLog();
        $log->setUser($user);
        $log->setTraining($training);

        $this->trainingLogRepository->method('findActiveByUserAndTraining')->willReturn($log);

        $result = $this->service->completeSession($user, $training, 600, 50);

        self::assertSame(UserTrainingLog::STATUS_COMPLETED, $result->getStatus());
        self::assertNotNull($result->getCompletedAt());
        self::assertSame(600, $result->getDurationSeconds());
        self::assertSame(50, $result->getXpEarned());
    }

    public function testCompleteSessionFailsWhenSessionIsNotActive(): void
    {
        // Una sesión abandonada no es activa: el repositorio no la devuelve
        // y completarla debe fallar igual que si no existiera.
        $this->trainingLogRepository->method('findActiveByUserAndTraining')->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No hay una sesión activa para este entrenamiento.');

        $this->service->completeSession(new User(), $this->createMinimalTraining(), null, 50);
    }

    public function testApplyStepActionFailsOnAbandonedSession(): void
    {
        $log = new UserTrainingLog();
        $log->setStatus(UserTrainingLog::STATUS_ABANDONED);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('abandonada');

        $this->service->applyStepAction($log, new UserTrainingSessionStep(), 'complete', 'step');
    }

    public function testGoBackFailsOnAbandonedSession(): void
    {
        $log = new UserTrainingLog();
        $log->setStatus(UserTrainingLog::STATUS_ABANDONED);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('abandonada');

        $this->service->goBack($log);
    }

    public function testAbandonOpenSessionsForProgramReturnsMarkedCount(): void
    {
        $user = new User();
        $program = new TrainingProgram();

        $this->trainingLogRepository->expects(self::once())
            ->method('abandonOpenSessionsForProgram')
            ->with($user, $program)
            ->willReturn(2);

        self::assertSame(2, $this->service->abandonOpenSessionsForProgram($user, $program));
    }

    private function createMinimalTraining(): Training
    {
        $training = new Training();
        $training->setName('Minimal Training');

        $round = new TrainingRound();
        $round->setSetsForRound(1);
        $round->setRestBetweenRounds(60);

        $exercise = new Exercises();
        $exercise->setName('Push Up');

        $config = new TrainingExerciseConfiguration();
        $config->setExercise($exercise);
        $config->setReps(10);
        $config->setSets(1);
        $config->setRestBetweenSets(60);
        $config->setRestBetweenExercises(30);

        $round->addTrainingExerciseConfiguration($config);
        $training->addTrainingRound($round);

        return $training;
    }
}
