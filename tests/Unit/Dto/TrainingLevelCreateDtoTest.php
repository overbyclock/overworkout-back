<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\TrainingLevelCreateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TrainingLevelCreateDtoTest extends TestCase
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
        $dto = new TrainingLevelCreateDto(
            name: 'Nivel 1: Fundamentos',
            programId: 1,
            levelNumber: 1,
            title: 'Fundamentos',
            description: 'Base técnica',
            objective: 'Aprender base',
            estimatedDurationWeeks: 4,
            difficultyRating: 2,
            color: '#4ade80',
            icon: 'fitness_center',
            requirementsSummary: 'Push-ups(10)',
            tips: ['Mantén buena forma'],
            testRequirements: ['pushups' => 10],
            skillFocus: 'Wall Handstand',
            programVersion: 'v1',
            isLockedByDefault: true,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testNameIsRequired(): void
    {
        $dto = new TrainingLevelCreateDto(
            name: '',
            programId: 1,
            levelNumber: 1,
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
    }

    public function testProgramIdMustBePositive(): void
    {
        $dto = new TrainingLevelCreateDto(
            name: 'Nivel 1',
            programId: -1,
            levelNumber: 1,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testLevelNumberMustBePositive(): void
    {
        $dto = new TrainingLevelCreateDto(
            name: 'Nivel 1',
            programId: 1,
            levelNumber: 0,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testDifficultyRatingRange(): void
    {
        $dto = new TrainingLevelCreateDto(
            name: 'Nivel 1',
            programId: 1,
            levelNumber: 1,
            difficultyRating: 11,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testDefaultValues(): void
    {
        $dto = new TrainingLevelCreateDto(
            name: 'Nivel 1',
            programId: 1,
            levelNumber: 1,
        );

        $this->assertEquals(12, $dto->estimatedDurationWeeks);
        $this->assertEquals('v1', $dto->programVersion);
        $this->assertTrue($dto->isLockedByDefault);
    }
}
