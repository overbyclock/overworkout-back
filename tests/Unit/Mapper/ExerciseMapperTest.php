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
        $this->entityManager = $this->createStub(EntityManagerInterface::class);
        $this->equipmentRepository = $this->createStub(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->willReturnCallback(fn (string $class) => Equipments::class === $class ? $this->equipmentRepository : null);

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

    public function testFromCreateDtoMapsGuidanceFields(): void
    {
        $dto = new ExerciseCreateDto(
            name: 'Thruster',
            primaryMuscleGroup: 'full_body',
            level: 'intermediate',
            setupInstructions: 'Barra en front rack.',
            executionInstructions: 'Sentadilla completa y empuje overhead.',
            movementStandards: 'Cadera debajo de la rodilla.',
            commonFaults: ['No bloquear codos'],
            safetyTips: 'Mantén el core activo.'
        );

        $exercise = $this->mapper->fromCreateDto($dto);

        $this->assertEquals('Barra en front rack.', $exercise->getSetupInstructions());
        $this->assertEquals('Sentadilla completa y empuje overhead.', $exercise->getExecutionInstructions());
        $this->assertEquals('Cadera debajo de la rodilla.', $exercise->getMovementStandards());
        $this->assertEquals(['No bloquear codos'], $exercise->getCommonFaults());
        $this->assertEquals('Mantén el core activo.', $exercise->getSafetyTips());
    }

    public function testFromCreateDtoWithEquipment(): void
    {
        $equipment = new Equipments();
        $equipment->setName('Barra');

        $this->equipmentRepository
            ->method('find')
            ->willReturnCallback(fn ($id) => 1 === $id ? $equipment : null);

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

    public function testUpdateFromDtoUpdatesGuidanceFields(): void
    {
        $exercise = new Exercises();
        $exercise->setName('Squat');

        $dto = new ExerciseUpdateDto(
            setupInstructions: 'Pies a anchura de hombros.',
            executionInstructions: 'Baja controladamente.',
            movementStandards: 'Cadera por debajo de la rodilla.',
            commonFaults: ['Levantar talones'],
            safetyTips: 'No hiperextender la espalda.'
        );

        $this->mapper->updateFromDto($exercise, $dto);

        $this->assertEquals('Pies a anchura de hombros.', $exercise->getSetupInstructions());
        $this->assertEquals('Baja controladamente.', $exercise->getExecutionInstructions());
        $this->assertEquals('Cadera por debajo de la rodilla.', $exercise->getMovementStandards());
        $this->assertEquals(['Levantar talones'], $exercise->getCommonFaults());
        $this->assertEquals('No hiperextender la espalda.', $exercise->getSafetyTips());
    }

    public function testHasChanges(): void
    {
        $dtoNoChanges = new ExerciseUpdateDto();
        $this->assertFalse($dtoNoChanges->hasChanges());

        $dtoWithChanges = new ExerciseUpdateDto(name: 'Test');
        $this->assertTrue($dtoWithChanges->hasChanges());
    }

    public function testHasChangesWithGuidanceFields(): void
    {
        $dto = new ExerciseUpdateDto(executionInstructions: 'Nuevo paso a paso');
        $this->assertTrue($dto->hasChanges());
    }
}
