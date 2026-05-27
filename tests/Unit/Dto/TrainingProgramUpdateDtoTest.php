<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\TrainingProgramUpdateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TrainingProgramUpdateDtoTest extends TestCase
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
        $dto = new TrainingProgramUpdateDto(
            name: 'Updated',
            slug: 'updated-slug',
            discipline: 'crossfit',
            difficulty: 'expert',
            totalLevels: 6,
            estimatedDurationWeeks: 24,
            imageUrl: 'https://example.com/updated.jpg',
            description: 'Updated description',
            isActive: false,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testEmptyDtoIsValid(): void
    {
        $dto = new TrainingProgramUpdateDto();

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testHasChangesReturnsFalseWhenEmpty(): void
    {
        $dto = new TrainingProgramUpdateDto();

        $this->assertFalse($dto->hasChanges());
    }

    public function testHasChangesReturnsTrueWithAnyField(): void
    {
        $dto = new TrainingProgramUpdateDto(name: 'Updated');

        $this->assertTrue($dto->hasChanges());
    }

    public function testInvalidSlugFormat(): void
    {
        $dto = new TrainingProgramUpdateDto(slug: 'Invalid Slug');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testInvalidDiscipline(): void
    {
        $dto = new TrainingProgramUpdateDto(discipline: 'invalid');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testInvalidDifficulty(): void
    {
        $dto = new TrainingProgramUpdateDto(difficulty: 'invalid');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testTotalLevelsMustBePositive(): void
    {
        $dto = new TrainingProgramUpdateDto(totalLevels: 0);

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }
}
