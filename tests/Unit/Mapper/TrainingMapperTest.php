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
use App\Entity\TrainingRound;
use App\Entity\User;

use App\Mapper\TrainingMapper;
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

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->exerciseRepository = $this->createMock(EntityRepository::class);
        $this->roundRepository = $this->createMock(EntityRepository::class);
        $this->configRepository = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->willReturnMap([
                [Exercises::class, $this->exerciseRepository],
                [TrainingRound::class, $this->roundRepository],
                [TrainingExerciseConfiguration::class, $this->configRepository],
            ]);

        $this->mapper = new TrainingMapper($this->entityManager);
    }

    public function testFromCreateDtoCreatesTraining(): void
    {
        $exercise = new Exercises();
        $exercise->setName('Push Up');
        $this->exerciseRepository
            ->method('find')
            ->with(1)
            ->willReturn($exercise);

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
                        )
                    ]
                )
            ]
        );

        $user = new User();
        $training = $this->mapper->fromCreateDto($dto, $user);

        $this->assertInstanceOf(Training::class, $training);
        $this->assertEquals('calisthenics', $training->getDiscipline()->value);
        $this->assertEquals('strength', $training->getTarget()->value);
        $this->assertEquals('Test Training', $training->getName());
        $this->assertCount(1, $training->getTrainingRounds());
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
            ->with(999)
            ->willReturn(null);

        $dto = new TrainingCreateDto(
            discipline: 'calisthenics',
            target: 'strength',
            rounds: [
                new TrainingRoundDto(
                    round: 1,
                    exercises: [new TrainingExerciseConfigDto(exerciseId: 999)]
                )
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
    }
}
