<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\ExerciseCreateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class ExerciseCreateDtoTest extends TestCase
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
        $dto = new ExerciseCreateDto(
            name: 'Push Up',
            primaryMuscleGroup: 'chest',
            level: 'beginner',
            secondaryMuscleGroup: 'triceps',
            equipmentId: 1,
            media: 'https://example.com/pushup.mp4',
            difficultyRating: 2,
            description: 'Flexiones básicas',
            disciplines: ['calisthenics']
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testNameIsRequired(): void
    {
        $dto = new ExerciseCreateDto(
            name: '',
            primaryMuscleGroup: 'chest',
            level: 'beginner'
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, count($violations));
    }

    public function testPrimaryMuscleGroupIsRequired(): void
    {
        $dto = new ExerciseCreateDto(
            name: 'Push Up',
            primaryMuscleGroup: '',
            level: 'beginner'
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, count($violations));
    }

    public function testLevelIsRequired(): void
    {
        $dto = new ExerciseCreateDto(
            name: 'Push Up',
            primaryMuscleGroup: 'chest',
            level: ''
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, count($violations));
    }

    public function testInvalidMuscleGroup(): void
    {
        $dto = new ExerciseCreateDto(
            name: 'Push Up',
            primaryMuscleGroup: 'invalid_muscle',
            level: 'beginner'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testInvalidLevel(): void
    {
        $dto = new ExerciseCreateDto(
            name: 'Push Up',
            primaryMuscleGroup: 'chest',
            level: 'invalid_level'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testDifficultyRatingRange(): void
    {
        $dto = new ExerciseCreateDto(
            name: 'Push Up',
            primaryMuscleGroup: 'chest',
            level: 'beginner',
            difficultyRating: 6
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testEquipmentIdMustBePositive(): void
    {
        $dto = new ExerciseCreateDto(
            name: 'Push Up',
            primaryMuscleGroup: 'chest',
            level: 'beginner',
            equipmentId: -1
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testGetEnumMethods(): void
    {
        $dto = new ExerciseCreateDto(
            name: 'Squat',
            primaryMuscleGroup: 'legs',
            secondaryMuscleGroup: 'glutes',
            level: 'intermediate'
        );

        $this->assertEquals('legs', $dto->getPrimaryMuscleGroupEnum()->value);
        $this->assertEquals('glutes', $dto->getSecondaryMuscleGroupEnum()->value);
        $this->assertEquals('intermediate', $dto->getLevelEnum()->value);
    }

    public function testGetSecondaryMuscleGroupEnumReturnsNull(): void
    {
        $dto = new ExerciseCreateDto(
            name: 'Push Up',
            primaryMuscleGroup: 'chest',
            level: 'beginner'
        );

        $this->assertNull($dto->getSecondaryMuscleGroupEnum());
    }
}
