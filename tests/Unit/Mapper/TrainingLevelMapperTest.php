<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\Request\TrainingLevelCreateDto;
use App\Dto\Request\TrainingLevelUpdateDto;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Mapper\TrainingLevelMapper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class TrainingLevelMapperTest extends TestCase
{
    private TrainingLevelMapper $mapper;

    private $entityManager;

    private $programRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->programRepository = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->with(TrainingProgram::class)
            ->willReturn($this->programRepository);

        $this->mapper = new TrainingLevelMapper($this->entityManager);
    }

    public function testFromCreateDtoCreatesLevel(): void
    {
        $program = new TrainingProgram();
        $program->setName('Test Program');

        $this->programRepository
            ->method('find')
            ->with(1)
            ->willReturn($program);

        $dto = new TrainingLevelCreateDto(
            name: 'Nivel 1',
            programId: 1,
            levelNumber: 1,
            title: 'Fundamentos',
            description: 'Desc',
            objective: 'Obj',
            estimatedDurationWeeks: 4,
            difficultyRating: 2,
            color: '#4ade80',
            icon: 'fitness',
            requirementsSummary: 'Req',
            tips: ['Tip'],
            testRequirements: ['test' => 5],
            skillFocus: 'HS',
            programVersion: 'v1',
            isInfinite: true,
            isLockedByDefault: true,
        );

        $level = $this->mapper->fromCreateDto($dto);

        $this->assertInstanceOf(TrainingLevel::class, $level);
        $this->assertSame($program, $level->getProgram());
        $this->assertEquals('Nivel 1', $level->getName());
        $this->assertEquals(1, $level->getLevelNumber());
        $this->assertEquals('Fundamentos', $level->getTitle());
        $this->assertEquals('Desc', $level->getDescription());
        $this->assertEquals('Obj', $level->getObjective());
        $this->assertEquals(4, $level->getEstimatedDurationWeeks());
        $this->assertEquals(2, $level->getDifficultyRating());
        $this->assertEquals('#4ade80', $level->getColor());
        $this->assertEquals('fitness', $level->getIcon());
        $this->assertEquals('Req', $level->getRequirementsSummary());
        $this->assertEquals(['Tip'], $level->getTips());
        $this->assertEquals(['test' => 5], $level->getTestRequirements());
        $this->assertEquals('HS', $level->getSkillFocus());
        $this->assertEquals('v1', $level->getProgramVersion());
        $this->assertTrue($level->isInfinite());
        $this->assertTrue($level->isIsLockedByDefault());
    }

    public function testFromCreateDtoThrowsExceptionForInvalidProgram(): void
    {
        $this->programRepository
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $dto = new TrainingLevelCreateDto(
            name: 'Nivel 1',
            programId: 999,
            levelNumber: 1,
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Programa no encontrado: 999');

        $this->mapper->fromCreateDto($dto);
    }

    public function testUpdateFromDtoUpdatesFields(): void
    {
        $level = new TrainingLevel();
        $level->setName('Old');
        $level->setLevelNumber(1);

        $dto = new TrainingLevelUpdateDto(
            name: 'New',
            levelNumber: 2,
            difficultyRating: 5,
        );

        $this->mapper->updateFromDto($level, $dto);

        $this->assertEquals('New', $level->getName());
        $this->assertEquals(2, $level->getLevelNumber());
        $this->assertEquals(5, $level->getDifficultyRating());
    }

    public function testUpdateFromDtoIgnoresNullValues(): void
    {
        $level = new TrainingLevel();
        $level->setName('Original');
        $level->setLevelNumber(1);

        $dto = new TrainingLevelUpdateDto(description: 'Updated');

        $this->mapper->updateFromDto($level, $dto);

        $this->assertEquals('Original', $level->getName());
        $this->assertEquals(1, $level->getLevelNumber());
        $this->assertEquals('Updated', $level->getDescription());
    }

    public function testUpdateFromDtoSetsIsInfinite(): void
    {
        $level = new TrainingLevel();
        $level->setName('Original');
        $level->setLevelNumber(1);

        $dto = new TrainingLevelUpdateDto(isInfinite: true);

        $this->mapper->updateFromDto($level, $dto);

        $this->assertTrue($level->isInfinite());
    }

    public function testHasChanges(): void
    {
        $dtoNoChanges = new TrainingLevelUpdateDto();
        $this->assertFalse($dtoNoChanges->hasChanges());

        $dtoWithChanges = new TrainingLevelUpdateDto(name: 'Test');
        $this->assertTrue($dtoWithChanges->hasChanges());
    }
}
