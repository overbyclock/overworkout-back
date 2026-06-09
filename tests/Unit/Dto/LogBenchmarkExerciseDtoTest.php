<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\LogBenchmarkExerciseDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class LogBenchmarkExerciseDtoTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidDtoWithAllFields(): void
    {
        $dto = new LogBenchmarkExerciseDto(
            benchmarkExerciseId: 1,
            roundNumber: 1,
            repsCompleted: 10,
            weightUsed: '43 kg',
            distanceCompleted: 400,
            caloriesCompleted: 15,
            durationSeconds: 60,
            restTakenSeconds: 30,
            notes: 'Nota de prueba'
        );
        $violations = $this->validator->validate($dto);
        $this->assertCount(0, $violations);
    }

    public function testValidDtoWithOnlyRequired(): void
    {
        $dto = new LogBenchmarkExerciseDto(benchmarkExerciseId: 1);
        $violations = $this->validator->validate($dto);
        $this->assertCount(0, $violations);
    }

    public function testBenchmarkExerciseIdIsRequired(): void
    {
        $dto = new LogBenchmarkExerciseDto(benchmarkExerciseId: 0);
        $violations = $this->validator->validate($dto);
        $this->assertGreaterThanOrEqual(1, \count($violations));
    }

    public function testNegativeRepsInvalid(): void
    {
        $dto = new LogBenchmarkExerciseDto(benchmarkExerciseId: 1, repsCompleted: -1);
        $violations = $this->validator->validate($dto);
        $this->assertCount(1, $violations);
    }

    public function testNegativeDurationInvalid(): void
    {
        $dto = new LogBenchmarkExerciseDto(benchmarkExerciseId: 1, durationSeconds: -5);
        $violations = $this->validator->validate($dto);
        $this->assertCount(1, $violations);
    }

    public function testWeightMaxLength(): void
    {
        $dto = new LogBenchmarkExerciseDto(benchmarkExerciseId: 1, weightUsed: str_repeat('a', 51));
        $violations = $this->validator->validate($dto);
        $this->assertCount(1, $violations);
    }
}
