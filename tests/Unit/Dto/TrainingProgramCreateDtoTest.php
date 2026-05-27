<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\TrainingProgramCreateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TrainingProgramCreateDtoTest extends TestCase
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
        $dto = new TrainingProgramCreateDto(
            name: 'Calistenia Master',
            slug: 'calistenia-master',
            discipline: 'calisthenics',
            difficulty: 'beginner',
            totalLevels: 12,
            estimatedDurationWeeks: 48,
            imageUrl: 'https://example.com/image.jpg',
            description: 'Programa completo de calistenia',
            isActive: true,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testNameIsRequired(): void
    {
        $dto = new TrainingProgramCreateDto(
            name: '',
            slug: 'test',
            discipline: 'calisthenics',
            difficulty: 'beginner',
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
    }

    public function testSlugIsRequired(): void
    {
        $dto = new TrainingProgramCreateDto(
            name: 'Test',
            slug: '',
            discipline: 'calisthenics',
            difficulty: 'beginner',
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
    }

    public function testInvalidSlugFormat(): void
    {
        $dto = new TrainingProgramCreateDto(
            name: 'Test',
            slug: 'Invalid Slug 123',
            discipline: 'calisthenics',
            difficulty: 'beginner',
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('solo puede contener', $violations[0]->getMessage());
    }

    public function testInvalidDiscipline(): void
    {
        $dto = new TrainingProgramCreateDto(
            name: 'Test',
            slug: 'test',
            discipline: 'invalid',
            difficulty: 'beginner',
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testInvalidDifficulty(): void
    {
        $dto = new TrainingProgramCreateDto(
            name: 'Test',
            slug: 'test',
            discipline: 'calisthenics',
            difficulty: 'invalid',
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testTotalLevelsMustBePositive(): void
    {
        $dto = new TrainingProgramCreateDto(
            name: 'Test',
            slug: 'test',
            discipline: 'calisthenics',
            difficulty: 'beginner',
            totalLevels: 0,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testEstimatedDurationWeeksMustBePositiveOrZero(): void
    {
        $dto = new TrainingProgramCreateDto(
            name: 'Test',
            slug: 'test',
            discipline: 'calisthenics',
            difficulty: 'beginner',
            estimatedDurationWeeks: -1,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }
}
