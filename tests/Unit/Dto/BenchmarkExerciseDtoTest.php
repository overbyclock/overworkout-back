<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\BenchmarkExerciseDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class BenchmarkExerciseDtoTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidDto(): void
    {
        $dto = new BenchmarkExerciseDto(
            exerciseId: 1,
            position: 1,
            reps: 21,
            rxWeightMale: '70kg',
            rxWeightFemale: '50kg'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testExerciseIdMustBePositive(): void
    {
        $dto = new BenchmarkExerciseDto(
            exerciseId: 0,
            position: 1
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $this->assertEquals('El ejercicio es obligatorio', $violations[0]->getMessage());
    }

    public function testPositionMustBePositive(): void
    {
        $dto = new BenchmarkExerciseDto(
            exerciseId: 1,
            position: -1
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('La posición debe ser mayor que 0', $violations[0]->getMessage());
    }

    public function testRepsCannotBeNegative(): void
    {
        $dto = new BenchmarkExerciseDto(
            exerciseId: 1,
            position: 1,
            reps: -5
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('Las repeticiones no pueden ser negativas', $violations[0]->getMessage());
    }

    public function testDistanceMetersCannotBeNegative(): void
    {
        $dto = new BenchmarkExerciseDto(
            exerciseId: 1,
            position: 1,
            distanceMeters: -100
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('La distancia no puede ser negativa', $violations[0]->getMessage());
    }

    public function testDurationSecondsCannotBeNegative(): void
    {
        $dto = new BenchmarkExerciseDto(
            exerciseId: 1,
            position: 1,
            durationSeconds: -30
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('La duración no puede ser negativa', $violations[0]->getMessage());
    }

    public function testCaloriesCannotBeNegative(): void
    {
        $dto = new BenchmarkExerciseDto(
            exerciseId: 1,
            position: 1,
            calories: -10
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('Las calorías no pueden ser negativas', $violations[0]->getMessage());
    }

    public function testRestSecondsCannotBeNegative(): void
    {
        $dto = new BenchmarkExerciseDto(
            exerciseId: 1,
            position: 1,
            restSeconds: -5
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('El descanso no puede ser negativo', $violations[0]->getMessage());
    }

    public function testAllOptionalFieldsCanBeNull(): void
    {
        $dto = new BenchmarkExerciseDto(
            exerciseId: 1,
            position: 1
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }
}
