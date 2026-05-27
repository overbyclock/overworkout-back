<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\TrainingLevelUpdateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TrainingLevelUpdateDtoTest extends TestCase
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
        $dto = new TrainingLevelUpdateDto(
            name: 'Updated',
            levelNumber: 2,
            title: 'Updated Title',
            description: 'Updated desc',
            objective: 'Updated obj',
            estimatedDurationWeeks: 6,
            difficultyRating: 5,
            color: '#ff0000',
            icon: 'star',
            requirementsSummary: 'Updated req',
            tips: ['Tip 1'],
            testRequirements: ['test' => 5],
            skillFocus: 'Planche',
            programVersion: 'v2',
            isLockedByDefault: false,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testEmptyDtoIsValid(): void
    {
        $dto = new TrainingLevelUpdateDto();

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testHasChangesReturnsFalseWhenEmpty(): void
    {
        $dto = new TrainingLevelUpdateDto();

        $this->assertFalse($dto->hasChanges());
    }

    public function testHasChangesReturnsTrueWithAnyField(): void
    {
        $dto = new TrainingLevelUpdateDto(name: 'Updated');

        $this->assertTrue($dto->hasChanges());
    }

    public function testDifficultyRatingRange(): void
    {
        $dto = new TrainingLevelUpdateDto(difficultyRating: 0);

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testLevelNumberMustBePositive(): void
    {
        $dto = new TrainingLevelUpdateDto(levelNumber: -1);

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }
}
