<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\Request\TrainingSkillCreateDto;
use App\Dto\Request\TrainingSkillUpdateDto;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingSkill;
use App\Mapper\TrainingSkillMapper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class TrainingSkillMapperTest extends TestCase
{
    private TrainingSkillMapper $mapper;

    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->mapper = new TrainingSkillMapper($this->entityManager);
    }

    public function testFromCreateDtoCreatesSkill(): void
    {
        $program = new TrainingProgram();
        $program->setName('Calistenia Master');
        $program->setSlug('calistenia-master');
        $program->setDiscipline('calisthenics');
        $program->setDifficulty('intermediate');

        $level = new TrainingLevel();
        $level->setName('Nivel 1');
        $level->setLevelNumber(1);
        $level->setProgram($program);

        $programRepo = $this->createMock(EntityRepository::class);
        $programRepo->method('find')->with(1)->willReturn($program);

        $levelRepo = $this->createMock(EntityRepository::class);
        $levelRepo->method('find')->with(1)->willReturn($level);

        $this->entityManager->method('getRepository')
            ->willReturnMap([
                [TrainingProgram::class, $programRepo],
                [TrainingLevel::class, $levelRepo],
            ]);

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

        $skill = $this->mapper->fromCreateDto($dto);

        $this->assertInstanceOf(TrainingSkill::class, $skill);
        $this->assertEquals('Handstand Wall', $skill->getName());
        $this->assertEquals($program, $skill->getProgram());
        $this->assertEquals($level, $skill->getLevel());
        $this->assertEquals('handstand', $skill->getFamily());
        $this->assertEquals(1, $skill->getUnlockAtLevel());
        $this->assertTrue($skill->isIsKeySkill());
    }

    public function testFromCreateDtoThrowsWhenProgramNotFound(): void
    {
        $programRepo = $this->createMock(EntityRepository::class);
        $programRepo->method('find')->willReturn(null);

        $this->entityManager->method('getRepository')
            ->with(TrainingProgram::class)
            ->willReturn($programRepo);

        $dto = new TrainingSkillCreateDto(
            name: 'Test',
            family: 'test',
            programId: 999,
            levelId: 1,
            unlockAtLevel: 1
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Programa no encontrado');

        $this->mapper->fromCreateDto($dto);
    }

    public function testUpdateFromDtoUpdatesFields(): void
    {
        $skill = new TrainingSkill();
        $skill->setName('Old');
        $skill->setFamily('old');
        $skill->setUnlockAtLevel(1);

        $dto = new TrainingSkillUpdateDto(name: 'New', family: 'new', unlockAtLevel: 2);

        $this->mapper->updateFromDto($skill, $dto);

        $this->assertEquals('New', $skill->getName());
        $this->assertEquals('new', $skill->getFamily());
        $this->assertEquals(2, $skill->getUnlockAtLevel());
    }

    public function testUpdateFromDtoDoesNotChangeNullFields(): void
    {
        $skill = new TrainingSkill();
        $skill->setName('Original');
        $skill->setFamily('original');
        $skill->setUnlockAtLevel(1);

        $dto = new TrainingSkillUpdateDto();

        $this->mapper->updateFromDto($skill, $dto);

        $this->assertEquals('Original', $skill->getName());
        $this->assertEquals('original', $skill->getFamily());
        $this->assertEquals(1, $skill->getUnlockAtLevel());
    }
}
