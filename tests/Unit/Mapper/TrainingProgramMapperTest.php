<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\Request\TrainingProgramCreateDto;
use App\Dto\Request\TrainingProgramUpdateDto;
use App\Entity\TrainingProgram;
use App\Mapper\TrainingProgramMapper;
use PHPUnit\Framework\TestCase;

class TrainingProgramMapperTest extends TestCase
{
    private TrainingProgramMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new TrainingProgramMapper();
    }

    public function testFromCreateDtoCreatesProgram(): void
    {
        $dto = new TrainingProgramCreateDto(
            name: 'Calistenia Master',
            slug: 'calistenia-master',
            discipline: 'calisthenics',
            difficulty: 'beginner',
            totalLevels: 12,
            estimatedDurationWeeks: 48,
            imageUrl: 'https://example.com/image.jpg',
            description: 'Programa completo',
            isActive: true,
        );

        $program = $this->mapper->fromCreateDto($dto);

        $this->assertInstanceOf(TrainingProgram::class, $program);
        $this->assertEquals('Calistenia Master', $program->getName());
        $this->assertEquals('calistenia-master', $program->getSlug());
        $this->assertEquals('calisthenics', $program->getDiscipline());
        $this->assertEquals('beginner', $program->getDifficulty());
        $this->assertEquals(12, $program->getTotalLevels());
        $this->assertEquals(48, $program->getEstimatedDurationWeeks());
        $this->assertEquals('https://example.com/image.jpg', $program->getImageUrl());
        $this->assertEquals('Programa completo', $program->getDescription());
        $this->assertTrue($program->isIsActive());
    }

    public function testUpdateFromDtoUpdatesFields(): void
    {
        $program = new TrainingProgram();
        $program->setName('Old');
        $program->setSlug('old-slug');

        $dto = new TrainingProgramUpdateDto(
            name: 'New',
            slug: 'new-slug',
            difficulty: 'intermediate',
        );

        $this->mapper->updateFromDto($program, $dto);

        $this->assertEquals('New', $program->getName());
        $this->assertEquals('new-slug', $program->getSlug());
        $this->assertEquals('intermediate', $program->getDifficulty());
    }

    public function testUpdateFromDtoIgnoresNullValues(): void
    {
        $program = new TrainingProgram();
        $program->setName('Original');
        $program->setTotalLevels(12);

        $dto = new TrainingProgramUpdateDto(description: 'Updated');

        $this->mapper->updateFromDto($program, $dto);

        $this->assertEquals('Original', $program->getName());
        $this->assertEquals(12, $program->getTotalLevels());
        $this->assertEquals('Updated', $program->getDescription());
    }

    public function testHasChanges(): void
    {
        $dtoNoChanges = new TrainingProgramUpdateDto();
        $this->assertFalse($dtoNoChanges->hasChanges());

        $dtoWithChanges = new TrainingProgramUpdateDto(name: 'Test');
        $this->assertTrue($dtoWithChanges->hasChanges());
    }
}
