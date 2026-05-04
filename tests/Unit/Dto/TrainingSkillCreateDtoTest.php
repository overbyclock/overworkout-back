<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\TrainingSkillCreateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TrainingSkillCreateDtoTest extends TestCase
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
        $dto = new TrainingSkillCreateDto(
            name: 'Handstand Wall',
            family: 'handstand',
            programId: 1,
            levelId: 1,
            unlockAtLevel: 1,
            description: 'Apoyo en pared',
            icon: 'handstand',
            masteryAtLevel: 5,
            videoTutorialUrl: 'https://example.com/video',
            difficultyScore: 3,
            isKeySkill: true
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testNameIsRequired(): void
    {
        $dto = new TrainingSkillCreateDto(
            name: '',
            family: 'handstand',
            programId: 1,
            levelId: 1,
            unlockAtLevel: 1
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $this->assertEquals('El nombre es obligatorio', $violations[0]->getMessage());
    }

    public function testNameMaxLength(): void
    {
        $dto = new TrainingSkillCreateDto(
            name: str_repeat('a', 256),
            family: 'handstand',
            programId: 1,
            levelId: 1,
            unlockAtLevel: 1
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('255', $violations[0]->getMessage());
    }

    public function testProgramIdIsRequired(): void
    {
        $dto = new TrainingSkillCreateDto(
            name: 'Test',
            family: 'handstand',
            programId: null,
            levelId: 1,
            unlockAtLevel: 1
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $messages = array_map(static fn ($v) => $v->getMessage(), iterator_to_array($violations));
        $this->assertContains('El programa es obligatorio', $messages);
    }

    public function testLevelIdIsRequired(): void
    {
        $dto = new TrainingSkillCreateDto(
            name: 'Test',
            family: 'handstand',
            programId: 1,
            levelId: null,
            unlockAtLevel: 1
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $messages = array_map(static fn ($v) => $v->getMessage(), iterator_to_array($violations));
        $this->assertContains('El nivel es obligatorio', $messages);
    }

    public function testFamilyIsRequired(): void
    {
        $dto = new TrainingSkillCreateDto(
            name: 'Test',
            family: '',
            programId: 1,
            levelId: 1,
            unlockAtLevel: 1
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $this->assertEquals('La familia es obligatoria', $violations[0]->getMessage());
    }

    public function testUnlockAtLevelIsRequired(): void
    {
        $dto = new TrainingSkillCreateDto(
            name: 'Test',
            family: 'handstand',
            programId: 1,
            levelId: 1,
            unlockAtLevel: null
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $messages = array_map(static fn ($v) => $v->getMessage(), iterator_to_array($violations));
        $this->assertContains('El nivel de desbloqueo es obligatorio', $messages);
    }

    public function testOptionalFieldsCanBeNull(): void
    {
        $dto = new TrainingSkillCreateDto(
            name: 'Test Skill',
            family: 'test',
            programId: 1,
            levelId: 1,
            unlockAtLevel: 0
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testUnlockAtLevelMustBePositiveOrZero(): void
    {
        $dto = new TrainingSkillCreateDto(
            name: 'Test',
            family: 'handstand',
            programId: 1,
            levelId: 1,
            unlockAtLevel: -1
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('positivo o cero', $violations[0]->getMessage());
    }
}
