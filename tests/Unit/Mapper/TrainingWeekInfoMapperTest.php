<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\Request\TrainingWeekInfoCreateDto;
use App\Dto\Request\TrainingWeekInfoUpdateDto;
use App\Entity\TrainingLevel;
use App\Entity\TrainingWeekInfo;
use App\Mapper\TrainingWeekInfoMapper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class TrainingWeekInfoMapperTest extends TestCase
{
    private TrainingWeekInfoMapper $mapper;

    private $entityManager;

    private $levelRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->levelRepository = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->with(TrainingLevel::class)
            ->willReturn($this->levelRepository);

        $this->mapper = new TrainingWeekInfoMapper($this->entityManager);
    }

    public function testFromCreateDtoCreatesWeekInfo(): void
    {
        $level = new TrainingLevel();
        $level->setName('Nivel 1');

        $this->levelRepository
            ->method('find')
            ->with(1)
            ->willReturn($level);

        $dto = new TrainingWeekInfoCreateDto(
            name: 'Semana 1',
            levelId: 1,
            weekNumber: 1,
            focus: 'Fuerza',
            note: 'Nota',
            intensity: 'media',
        );

        $weekInfo = $this->mapper->fromCreateDto($dto);

        $this->assertInstanceOf(TrainingWeekInfo::class, $weekInfo);
        $this->assertSame($level, $weekInfo->getTrainingLevel());
        $this->assertEquals('Semana 1', $weekInfo->getName());
        $this->assertEquals(1, $weekInfo->getWeekNumber());
        $this->assertEquals('Fuerza', $weekInfo->getFocus());
        $this->assertEquals('Nota', $weekInfo->getNote());
        $this->assertEquals('media', $weekInfo->getIntensity());
    }

    public function testFromCreateDtoThrowsExceptionForInvalidLevel(): void
    {
        $this->levelRepository
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $dto = new TrainingWeekInfoCreateDto(
            name: 'Semana 1',
            levelId: 999,
            weekNumber: 1,
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Nivel no encontrado: 999');

        $this->mapper->fromCreateDto($dto);
    }

    public function testUpdateFromDtoUpdatesFields(): void
    {
        $weekInfo = new TrainingWeekInfo();
        $weekInfo->setName('Old');
        $weekInfo->setWeekNumber(1);

        $dto = new TrainingWeekInfoUpdateDto(
            name: 'New',
            weekNumber: 2,
            intensity: 'alta',
        );

        $this->mapper->updateFromDto($weekInfo, $dto);

        $this->assertEquals('New', $weekInfo->getName());
        $this->assertEquals(2, $weekInfo->getWeekNumber());
        $this->assertEquals('alta', $weekInfo->getIntensity());
    }

    public function testUpdateFromDtoIgnoresNullValues(): void
    {
        $weekInfo = new TrainingWeekInfo();
        $weekInfo->setName('Original');
        $weekInfo->setWeekNumber(1);

        $dto = new TrainingWeekInfoUpdateDto(note: 'Updated');

        $this->mapper->updateFromDto($weekInfo, $dto);

        $this->assertEquals('Original', $weekInfo->getName());
        $this->assertEquals(1, $weekInfo->getWeekNumber());
        $this->assertEquals('Updated', $weekInfo->getNote());
    }

    public function testHasChanges(): void
    {
        $dtoNoChanges = new TrainingWeekInfoUpdateDto();
        $this->assertFalse($dtoNoChanges->hasChanges());

        $dtoWithChanges = new TrainingWeekInfoUpdateDto(name: 'Test');
        $this->assertTrue($dtoWithChanges->hasChanges());
    }
}
