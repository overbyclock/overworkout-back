<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\TrainingCreateDto;
use App\Dto\Request\TrainingExerciseConfigDto;
use App\Dto\Request\TrainingRoundDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TrainingCreateDtoTest extends TestCase
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
        $dto = new TrainingCreateDto(
            discipline: 'calisthenics',
            target: 'strength',
            name: 'Mi Entrenamiento',
            rounds: [
                new TrainingRoundDto(
                    round: 1,
                    restBetweenRounds: 60,
                    exercises: [
                        new TrainingExerciseConfigDto(
                            exerciseId: 1,
                            reps: 10,
                            sets: 3
                        ),
                    ]
                ),
            ]
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testDisciplineCannotBeBlank(): void
    {
        $dto = new TrainingCreateDto(
            discipline: '',
            target: 'strength',
            rounds: []
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $disciplineViolation = $this->findViolationForProperty($violations, 'discipline');
        $this->assertNotNull($disciplineViolation);
    }

    public function testDisciplineMustBeValid(): void
    {
        $dto = new TrainingCreateDto(
            discipline: 'invalid_discipline',
            target: 'strength',
            rounds: []
        );

        $violations = $this->validator->validate($dto);

        $disciplineViolation = $this->findViolationForProperty($violations, 'discipline');
        $this->assertNotNull($disciplineViolation);
        $this->assertStringContainsString('no es válida', $disciplineViolation->getMessage());
    }

    public function testTargetCannotBeBlank(): void
    {
        $dto = new TrainingCreateDto(
            discipline: 'calisthenics',
            target: '',
            rounds: []
        );

        $violations = $this->validator->validate($dto);

        $targetViolation = $this->findViolationForProperty($violations, 'target');
        $this->assertNotNull($targetViolation);
    }

    public function testTargetMustBeValid(): void
    {
        $dto = new TrainingCreateDto(
            discipline: 'calisthenics',
            target: 'invalid_target',
            rounds: []
        );

        $violations = $this->validator->validate($dto);

        $targetViolation = $this->findViolationForProperty($violations, 'target');
        $this->assertNotNull($targetViolation);
        $this->assertStringContainsString('no es válido', $targetViolation->getMessage());
    }

    public function testRoundsCannotBeEmpty(): void
    {
        $dto = new TrainingCreateDto(
            discipline: 'calisthenics',
            target: 'strength',
            rounds: []
        );

        $violations = $this->validator->validate($dto);

        $roundsViolation = $this->findViolationForProperty($violations, 'rounds');
        $this->assertNotNull($roundsViolation);
    }

    public function testNameIsOptional(): void
    {
        $dto = new TrainingCreateDto(
            discipline: 'calisthenics',
            target: 'strength',
            name: null,
            rounds: [
                new TrainingRoundDto(
                    round: 1,
                    exercises: [new TrainingExerciseConfigDto(exerciseId: 1)]
                ),
            ]
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testGetDisciplineEnum(): void
    {
        $dto = new TrainingCreateDto(
            discipline: 'crossfit',
            target: 'strength',
            rounds: [
                new TrainingRoundDto(
                    round: 1,
                    exercises: [new TrainingExerciseConfigDto(exerciseId: 1)]
                ),
            ]
        );

        $enum = $dto->getDisciplineEnum();
        $this->assertEquals('crossfit', $enum->value);
    }

    public function testGetTargetEnum(): void
    {
        $dto = new TrainingCreateDto(
            discipline: 'calisthenics',
            target: 'fatburning',
            rounds: [
                new TrainingRoundDto(
                    round: 1,
                    exercises: [new TrainingExerciseConfigDto(exerciseId: 1)]
                ),
            ]
        );

        $enum = $dto->getTargetEnum();
        $this->assertEquals('fatburning', $enum->value);
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $violations
     */
    private function findViolationForProperty($violations, string $property): ?\Symfony\Component\Validator\ConstraintViolationInterface
    {
        foreach ($violations as $violation) {
            if ($violation->getPropertyPath() === $property) {
                return $violation;
            }
        }

        return null;
    }
}
