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
use PHPUnit\Framework\TestCase;

final class TrainingSessionServiceTest extends TestCase
{
    public function testStartSessionCreatesLogWithInProgressStatus(): void
    {
        // Un log abandoned ya no lo devuelve el repositorio como activo:
        // la nueva sesión debe nacer siempre como in_progress.
        $user = new User();
        $training = $this->createMinimalTraining();

        $trainingLogRepository = $this->createMock(UserTrainingLogRepository::class);
        $service = $this->createService(trainingLogRepository: $trainingLogRepository);

        $trainingLogRepository->expects(self::once())
            ->method('findActiveByUserAndTraining')
            ->with($user, $training)
            ->willReturn(null);

        $result = $service->startSession($user, $training);

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

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $trainingLogRepository = $this->createMock(UserTrainingLogRepository::class);
        $service = $this->createService($entityManager, $trainingLogRepository);

        $trainingLogRepository->expects(self::once())
            ->method('findActiveByUserAndTraining')
            ->willReturn($existing);

        $entityManager->expects(self::never())->method('persist');

        $result = $service->startSession($user, $training);

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

        $trainingLogRepository = $this->createStub(UserTrainingLogRepository::class);
        $trainingLogRepository->method('findActiveByUserAndTraining')->willReturn($log);
        $service = $this->createService(trainingLogRepository: $trainingLogRepository);

        $result = $service->completeSession($user, $training, 600, 50);

        self::assertSame(UserTrainingLog::STATUS_COMPLETED, $result->getStatus());
        self::assertNotNull($result->getCompletedAt());
        self::assertSame(600, $result->getDurationSeconds());
        self::assertSame(50, $result->getXpEarned());
    }

    public function testCompleteSessionFailsWhenSessionIsNotActive(): void
    {
        // Una sesión abandonada no es activa: el repositorio no la devuelve
        // y completarla debe fallar igual que si no existiera.
        $trainingLogRepository = $this->createStub(UserTrainingLogRepository::class);
        $trainingLogRepository->method('findActiveByUserAndTraining')->willReturn(null);
        $service = $this->createService(trainingLogRepository: $trainingLogRepository);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No hay una sesión activa para este entrenamiento.');

        $service->completeSession(new User(), $this->createMinimalTraining(), null, 50);
    }

    public function testApplyStepActionFailsOnAbandonedSession(): void
    {
        $log = new UserTrainingLog();
        $log->setStatus(UserTrainingLog::STATUS_ABANDONED);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('abandonada');

        $this->createService()->applyStepAction($log, new UserTrainingSessionStep(), 'complete', 'step');
    }

    public function testGoBackFailsOnAbandonedSession(): void
    {
        $log = new UserTrainingLog();
        $log->setStatus(UserTrainingLog::STATUS_ABANDONED);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('abandonada');

        $this->createService()->goBack($log);
    }

    public function testAbandonOpenSessionsForProgramReturnsMarkedCount(): void
    {
        $user = new User();
        $program = new TrainingProgram();

        $trainingLogRepository = $this->createMock(UserTrainingLogRepository::class);
        $service = $this->createService(trainingLogRepository: $trainingLogRepository);

        $trainingLogRepository->expects(self::once())
            ->method('abandonOpenSessionsForProgram')
            ->with($user, $program)
            ->willReturn(2);

        self::assertSame(2, $service->abandonOpenSessionsForProgram($user, $program));
    }

    public function testGetNextSessionIncludesExercisesPerBlock(): void
    {
        $user = new User();
        $training = $this->createMinimalTraining();
        $log = new UserTrainingLog();
        $log->setUser($user);
        $log->setTraining($training);

        $trainingLogRepository = $this->createStub(UserTrainingLogRepository::class);
        $trainingLogRepository->method('findStartedButNotCompletedByUser')->willReturn($log);
        $service = $this->createService(trainingLogRepository: $trainingLogRepository);

        $result = $service->getNextSessionForUser($user);

        self::assertSame('continue', $result['type']);
        $blocks = $result['training']['blocks'];
        self::assertCount(1, $blocks);
        self::assertSame(
            [['name' => 'Push Up', 'sets' => 1, 'reps' => 10, 'durationSeconds' => null]],
            $blocks[0]['exercises'],
        );
        // Con una sola vuelta no se expone laps
        self::assertArrayNotHasKey('laps', $blocks[0]);
    }

    public function testGetNextSessionExposesLapsWhenRoundHasMultipleLaps(): void
    {
        $user = new User();
        $training = $this->createMinimalTraining();
        $training->getTrainingRounds()->first()->setSetsForRound(3);

        $log = new UserTrainingLog();
        $log->setUser($user);
        $log->setTraining($training);

        $trainingLogRepository = $this->createStub(UserTrainingLogRepository::class);
        $trainingLogRepository->method('findStartedButNotCompletedByUser')->willReturn($log);
        $service = $this->createService(trainingLogRepository: $trainingLogRepository);

        $result = $service->getNextSessionForUser($user);

        self::assertSame(3, $result['training']['blocks'][0]['laps']);
    }

    public function testGetNextSessionExposesDurationForTimeBasedExercises(): void
    {
        $user = new User();
        $training = new Training();
        $training->setName('Time Based Training');

        $round = new TrainingRound();
        $round->setSetsForRound(1);
        $round->setRestBetweenRounds(60);

        $exercise = new Exercises();
        $exercise->setName('Plancha');

        $config = new TrainingExerciseConfiguration();
        $config->setExercise($exercise);
        $config->setMaxTimeForReps(40);
        $config->setSets(3);
        $config->setRestBetweenSets(30);
        $config->setRestBetweenExercises(30);

        $round->addTrainingExerciseConfiguration($config);
        $training->addTrainingRound($round);

        $log = new UserTrainingLog();
        $log->setUser($user);
        $log->setTraining($training);

        $trainingLogRepository = $this->createStub(UserTrainingLogRepository::class);
        $trainingLogRepository->method('findStartedButNotCompletedByUser')->willReturn($log);
        $service = $this->createService(trainingLogRepository: $trainingLogRepository);

        $result = $service->getNextSessionForUser($user);

        self::assertSame(
            [['name' => 'Plancha', 'sets' => 3, 'reps' => null, 'durationSeconds' => 40]],
            $result['training']['blocks'][0]['exercises'],
        );
    }

    public function testGetTrainingPreviewReturnsSummaryWithExercises(): void
    {
        $service = $this->createService();

        $preview = $service->getTrainingPreview($this->createMinimalTraining());

        self::assertSame('Minimal Training', $preview['name']);
        self::assertCount(1, $preview['blocks']);
        self::assertSame(
            [['name' => 'Push Up', 'sets' => 1, 'reps' => 10, 'durationSeconds' => null]],
            $preview['blocks'][0]['exercises'],
        );
    }

    private function createService(
        ?EntityManagerInterface $entityManager = null,
        ?UserTrainingLogRepository $trainingLogRepository = null,
    ): TrainingSessionService {
        return new TrainingSessionService(
            $entityManager ?? $this->createStub(EntityManagerInterface::class),
            $this->createStub(UserLevelProgressRepository::class),
            $trainingLogRepository ?? $this->createStub(UserTrainingLogRepository::class),
            $this->createStub(TrainingLevelRepository::class),
            $this->createStub(LevelProgressService::class),
            new TrainingSessionExpander(),
        );
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
