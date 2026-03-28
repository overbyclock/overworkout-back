<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\Request\ExerciseCreateDto;
use App\Dto\Request\ExerciseUpdateDto;
use App\Entity\Equipments;
use App\Entity\Exercises;
use App\Mapper\ExerciseMapper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class ExerciseMapperTest extends TestCase
{
    private ExerciseMapper $mapper;

    private $entityManager;

    private $equipmentRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->equipmentRepository = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->with(Equipments::class)
            ->willReturn($this->equipmentRepository);

        $this->mapper = new ExerciseMapper($this->entityManager);
    }

    public function testFromCreateDtoCreatesExercise(): void
    {
        $dto = new ExerciseCreateDto(
            name: 'Push Up',
            primaryMuscleGroup: 'chest',
            level: 'beginner',
            secondaryMuscleGroup: 'triceps',
            equipmentId: null,
            difficultyRating: 2
        );

        $exercise = $this->mapper->fromCreateDto($dto);

        $this->assertInstanceOf(Exercises::class, $exercise);
        $this->assertEquals('Push Up', $exercise->getName());
        $this->assertEquals('chest', $exercise->getPrimaryMuscleGroup()->value);
        $this->assertEquals('triceps', $exercise->getSecondaryMuscleGroup()->value);
        $this->assertEquals('beginner', $exercise->getLevel()->value);
    }

    public function testFromCreateDtoWithEquipment(): void
    {
        $equipment = new Equipments();
        $equipment->setName('Barra');

        $this->equipmentRepository
            ->method('find')
            ->with(1)
            ->willReturn($equipment);

        $dto = new ExerciseCreateDto(
            name: 'Dominadas',
            primaryMuscleGroup: 'back',
            level: 'intermediate',
            equipmentId: 1
        );

        $exercise = $this->mapper->fromCreateDto($dto);

        $this->assertSame($equipment, $exercise->getEquipment());
    }

    public function testFromCreateDtoThrowsExceptionForInvalidEquipment(): void
    {
        $this->equipmentRepository
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $dto = new ExerciseCreateDto(
            name: 'Test',
            primaryMuscleGroup: 'chest',
            level: 'beginner',
            equipmentId: 999
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Equipment not found: 999');

        $this->mapper->fromCreateDto($dto);
    }

    public function testUpdateFromDtoUpdatesFields(): void
    {
        $exercise = new Exercises();
        $exercise->setName('Old Name');

        $dto = new ExerciseUpdateDto(name: 'New Name');

        $this->mapper->updateFromDto($exercise, $dto);

        $this->assertEquals('New Name', $exercise->getName());
    }

    public function testHasChanges(): void
    {
        $dtoNoChanges = new ExerciseUpdateDto();
        $this->assertFalse($dtoNoChanges->hasChanges());

        $dtoWithChanges = new ExerciseUpdateDto(name: 'Test');
        $this->assertTrue($dtoWithChanges->hasChanges());
    }
}
