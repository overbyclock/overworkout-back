<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\TrainingSkillUpdateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TrainingSkillUpdateDtoTest extends TestCase
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
        $dto = new TrainingSkillUpdateDto(
            name: 'Updated',
            programId: 2,
            levelId: 3,
            family: 'muscleup',
            unlockAtLevel: 2,
            masteryAtLevel: 6,
            difficultyScore: 4,
            isKeySkill: true
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testEmptyDtoIsValid(): void
    {
        $dto = new TrainingSkillUpdateDto();

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testHasChangesReturnsFalseWhenEmpty(): void
    {
        $dto = new TrainingSkillUpdateDto();

        $this->assertFalse($dto->hasChanges());
    }

    public function testHasChangesReturnsTrueWithAnyField(): void
    {
        $dto = new TrainingSkillUpdateDto(name: 'Updated');

        $this->assertTrue($dto->hasChanges());
    }

    public function testNameMaxLength(): void
    {
        $dto = new TrainingSkillUpdateDto(name: str_repeat('a', 256));

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('255', $violations[0]->getMessage());
    }

    public function testProgramIdMustBePositive(): void
    {
        $dto = new TrainingSkillUpdateDto(programId: -1);

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('positivo', $violations[0]->getMessage());
    }

    public function testDifficultyScoreMustBePositiveOrZero(): void
    {
        $dto = new TrainingSkillUpdateDto(difficultyScore: -1);

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('positiva o cero', $violations[0]->getMessage());
    }
}
