<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\Request\TrainingCreateDto;
use App\Dto\Request\TrainingExerciseConfigDto;
use App\Dto\Request\TrainingRoundDto;
use App\Dto\Request\TrainingUpdateDto;
use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingLevel;
use App\Entity\TrainingRound;
use App\Entity\User;

use App\Mapper\TrainingMapper;
use App\Service\TrainingTimeCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class TrainingMapperTest extends TestCase
{
    private TrainingMapper $mapper;

    private $entityManager;

    private $exerciseRepository;

    private $roundRepository;

    private $configRepository;

    private $timeCalculator;

    protected function setUp(): void
    {
        $this->entityManager = $this->createStub(EntityManagerInterface::class);
        $this->exerciseRepository = $this->createStub(EntityRepository::class);
        $this->roundRepository = $this->createStub(EntityRepository::class);
        $this->configRepository = $this->createStub(EntityRepository::class);
        $this->timeCalculator = $this->createStub(TrainingTimeCalculator::class);

        $this->entityManager
            ->method('getRepository')
            ->willReturnMap($this->createRepositoryMap());

        $this->timeCalculator
            ->method('calculate')
            ->willReturn(['min' => 120, 'max' => 180]);

        $this->mapper = new TrainingMapper($this->entityManager, $this->timeCalculator);
    }

    private function createRepositoryMap(): array
    {
        return [
            [Exercises::class, $this->exerciseRepository],
            [TrainingRound::class, $this->roundRepository],
            [TrainingExerciseConfiguration::class, $this->configRepository],
            [TrainingLevel::class, $this->createStub(EntityRepository::class)],
        ];
    }

    public function testFromCreateDtoCreatesTraining(): void
    {
        $exercise = new Exercises();
        $exercise->setName('Push Up');
        $this->exerciseRepository
            ->method('find')
            ->willReturnCallback(fn ($id) => 1 === $id ? $exercise : null);

        $dto = new TrainingCreateDto(
            discipline: 'calisthenics',
            target: 'strength',
            name: 'Test Training',
            rounds: [
                new TrainingRoundDto(
                    round: 1,
                    restBetweenRounds: 60,
                    exercises: [
                        new TrainingExerciseConfigDto(
                            exerciseId: 1,
                            reps: 10,
                            sets: 3,
                            restBetweenSets: 30
                        ),
                    ],
                    name: 'Preparación y movilidad',
                    description: 'Activación articular y movilidad específica.'
                ),
            ]
        );

        $user = new User();
        $training = $this->mapper->fromCreateDto($dto, $user);

        $this->assertInstanceOf(Training::class, $training);
        $this->assertEquals('calisthenics', $training->getDiscipline()->value);
        $this->assertEquals('strength', $training->getTarget()->value);
        $this->assertEquals('Test Training', $training->getName());
        $this->assertCount(1, $training->getTrainingRounds());
        $round = $training->getTrainingRounds()->first();
        $this->assertEquals('Preparación y movilidad', $round->getName());
        $this->assertEquals('Activación articular y movilidad específica.', $round->getDescription());
    }

    public function testFromCreateDtoWithMultipleRounds(): void
    {
        $exercise = new Exercises();
        $this->exerciseRepository->method('find')->willReturn($exercise);

        $dto = new TrainingCreateDto(
            discipline: 'crossfit',
            target: 'fatburning',
            rounds: [
                new TrainingRoundDto(round: 1, exercises: [new TrainingExerciseConfigDto(exerciseId: 1)]),
                new TrainingRoundDto(round: 2, exercises: [new TrainingExerciseConfigDto(exerciseId: 2)]),
            ]
        );

        $training = $this->mapper->fromCreateDto($dto, new User());

        $this->assertCount(2, $training->getTrainingRounds());
    }

    public function testFromCreateDtoThrowsExceptionForInvalidExercise(): void
    {
        $this->exerciseRepository
            ->method('find')
            ->willReturn(null);

        $dto = new TrainingCreateDto(
            discipline: 'calisthenics',
            target: 'strength',
            rounds: [
                new TrainingRoundDto(
                    round: 1,
                    exercises: [new TrainingExerciseConfigDto(exerciseId: 999)]
                ),
            ]
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Exercise not found: 999');

        $this->mapper->fromCreateDto($dto, new User());
    }

    public function testUpdateFromDtoUpdatesDiscipline(): void
    {
        $training = new Training();
        $training->setDiscipline(\App\Enum\Discipline::CALISTHENICS);

        $dto = new TrainingUpdateDto(discipline: 'crossfit');

        $this->mapper->updateFromDto($training, $dto);

        $this->assertEquals('crossfit', $training->getDiscipline()->value);
    }

    public function testUpdateFromDtoUpdatesTarget(): void
    {
        $training = new Training();
        $training->setTarget(\App\Enum\TargetWorkout::STRENGTH);

        $dto = new TrainingUpdateDto(target: 'fatburning');

        $this->mapper->updateFromDto($training, $dto);

        $this->assertEquals('fatburning', $training->getTarget()->value);
    }

    public function testUpdateFromDtoUpdatesName(): void
    {
        $training = new Training();
        $training->setName('Old Name');

        $dto = new TrainingUpdateDto(name: 'New Name');

        $this->mapper->updateFromDto($training, $dto);

        $this->assertEquals('New Name', $training->getName());
    }

    public function testUpdateFromDtoDoesNotChangeNullFields(): void
    {
        $training = new Training();
        $training->setDiscipline(\App\Enum\Discipline::CALISTHENICS);
        $training->setTarget(\App\Enum\TargetWorkout::STRENGTH);
        $training->setName('Original Name');

        $dto = new TrainingUpdateDto();

        $this->mapper->updateFromDto($training, $dto);

        $this->assertEquals('calisthenics', $training->getDiscipline()->value);
        $this->assertEquals('strength', $training->getTarget()->value);
        $this->assertEquals('Original Name', $training->getName());
    }

    public function testGetDisciplineEnumReturnsNullWhenNull(): void
    {
        $dto = new TrainingUpdateDto();

        $this->assertNull($dto->getDisciplineEnum());
    }

    public function testGetTargetEnumReturnsNullWhenNull(): void
    {
        $dto = new TrainingUpdateDto();

        $this->assertNull($dto->getTargetEnum());
    }

    public function testHasChangesReturnsFalseWhenNoChanges(): void
    {
        $dto = new TrainingUpdateDto();

        $this->assertFalse($dto->hasChanges());
    }

    public function testHasChangesReturnsTrueWhenAnyFieldSet(): void
    {
        $this->assertTrue((new TrainingUpdateDto(discipline: 'Calistenia'))->hasChanges());
        $this->assertTrue((new TrainingUpdateDto(target: 'Fuerza'))->hasChanges());
        $this->assertTrue((new TrainingUpdateDto(name: 'Test'))->hasChanges());
        $this->assertTrue((new TrainingUpdateDto(rounds: []))->hasChanges());
        $this->assertTrue((new TrainingUpdateDto(trainingLevelId: 1))->hasChanges());
        $this->assertTrue((new TrainingUpdateDto(weekNumber: 1))->hasChanges());
        $this->assertTrue((new TrainingUpdateDto(dayKey: 'day_a'))->hasChanges());
        $this->assertTrue((new TrainingUpdateDto(sessionType: 'strength'))->hasChanges());
    }

    public function testUpdateFromDtoReplacesRounds(): void
    {
        $training = new Training();
        $training->setDiscipline(\App\Enum\Discipline::CALISTHENICS);
        $training->setTarget(\App\Enum\TargetWorkout::STRENGTH);

        $oldRound = new TrainingRound();
        $oldRound->setRound(1);
        $oldRound->setRestBetweenRounds(60);
        $oldRound->setTraining($training);
        $training->addTrainingRound($oldRound);

        $reflection = new \ReflectionClass($oldRound);
        $idProp = $reflection->getProperty('id');
        $idProp->setValue($oldRound, 100);

        $exercise = new Exercises();
        $this->exerciseRepository->method('find')->willReturn($exercise);

        $dto = new TrainingUpdateDto(rounds: [
            new TrainingRoundDto(round: 1, restBetweenRounds: 90, exercises: [
                new TrainingExerciseConfigDto(exerciseId: 1, reps: 5),
            ]),
        ]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getRepository')
            ->willReturnMap($this->createRepositoryMap());
        $entityManager->expects($this->once())->method('remove')->with($oldRound);

        $mapper = new TrainingMapper($entityManager, $this->timeCalculator);
        $mapper->updateFromDto($training, $dto);

        $this->assertCount(1, $training->getTrainingRounds());
        $this->assertEquals(90, $training->getTrainingRounds()->first()->getRestBetweenRounds());
    }

    public function testUpdateFromDtoUpdatesExistingRound(): void
    {
        $training = new Training();
        $training->setDiscipline(\App\Enum\Discipline::CALISTHENICS);
        $training->setTarget(\App\Enum\TargetWorkout::STRENGTH);

        $existingRound = new TrainingRound();
        $existingRound->setRound(1);
        $existingRound->setRestBetweenRounds(60);
        $existingRound->setTraining($training);
        $training->addTrainingRound($existingRound);

        $reflection = new \ReflectionClass($existingRound);
        $idProp = $reflection->getProperty('id');
        $idProp->setValue($existingRound, 200);

        $this->roundRepository
            ->method('find')
            ->willReturnCallback(fn ($id) => 200 === $id ? $existingRound : null);

        $dto = new TrainingUpdateDto(rounds: [
            new TrainingRoundDto(round: 2, restBetweenRounds: 120, id: 200, exercises: [], name: 'Salud postural', description: 'Cuidado del hombro.'),
        ]);

        $this->mapper->updateFromDto($training, $dto);

        $this->assertCount(1, $training->getTrainingRounds());
        $this->assertEquals(2, $existingRound->getRound());
        $this->assertEquals(120, $existingRound->getRestBetweenRounds());
        $this->assertEquals('Salud postural', $existingRound->getName());
        $this->assertEquals('Cuidado del hombro.', $existingRound->getDescription());
    }

    public function testUpdateFromDtoReplacesExercisesInRound(): void
    {
        $training = new Training();
        $training->setDiscipline(\App\Enum\Discipline::CALISTHENICS);
        $training->setTarget(\App\Enum\TargetWorkout::STRENGTH);

        $round = new TrainingRound();
        $round->setRound(1);
        $round->setRestBetweenRounds(60);
        $round->setTraining($training);
        $training->addTrainingRound($round);

        $reflection = new \ReflectionClass($round);
        $idProp = $reflection->getProperty('id');
        $idProp->setValue($round, 300);

        $this->roundRepository
            ->method('find')
            ->willReturnCallback(fn ($id) => 300 === $id ? $round : null);

        $oldExercise = new TrainingExerciseConfiguration();
        $oldExercise->setSets(3);
        $oldExercise->setRestBetweenSets(30);
        $oldExercise->setTrainingRound($round);
        $round->addTrainingExerciseConfiguration($oldExercise);

        $exReflection = new \ReflectionClass($oldExercise);
        $exIdProp = $exReflection->getProperty('id');
        $exIdProp->setValue($oldExercise, 500);

        $exercise = new Exercises();
        $this->exerciseRepository->method('find')->willReturn($exercise);

        $dto = new TrainingUpdateDto(rounds: [
            new TrainingRoundDto(round: 1, restBetweenRounds: 60, id: 300, exercises: [
                new TrainingExerciseConfigDto(exerciseId: 1, reps: 10, sets: 4),
            ]),
        ]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getRepository')
            ->willReturnMap($this->createRepositoryMap());
        $entityManager->expects($this->once())->method('remove')->with($oldExercise);

        $mapper = new TrainingMapper($entityManager, $this->timeCalculator);
        $mapper->updateFromDto($training, $dto);

        $this->assertCount(1, $round->getTrainingExerciseConfigurations());
        $this->assertEquals(4, $round->getTrainingExerciseConfigurations()->first()->getSets());
    }
}
