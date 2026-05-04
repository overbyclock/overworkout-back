<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\Request\TrainingSkillCreateDto;
use App\Dto\Request\TrainingSkillUpdateDto;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingSkill;
use Doctrine\ORM\EntityManagerInterface;

readonly class TrainingSkillMapper
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function fromCreateDto(TrainingSkillCreateDto $dto): TrainingSkill
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($dto->programId);
        if (null === $program) {
            throw new \InvalidArgumentException('Programa no encontrado');
        }

        $level = $this->entityManager->getRepository(TrainingLevel::class)->find($dto->levelId);
        if (null === $level) {
            throw new \InvalidArgumentException('Nivel no encontrado');
        }

        $skill = new TrainingSkill();
        $skill->setProgram($program);
        $skill->setLevel($level);
        $skill->setName($dto->name);
        $skill->setFamily($dto->family);
        $skill->setDescription($dto->description);
        $skill->setIcon($dto->icon);
        $skill->setUnlockAtLevel($dto->unlockAtLevel);
        $skill->setMasteryAtLevel($dto->masteryAtLevel);
        $skill->setVideoTutorialUrl($dto->videoTutorialUrl);
        $skill->setDifficultyScore($dto->difficultyScore);
        $skill->setIsKeySkill($dto->isKeySkill ?? false);

        return $skill;
    }

    public function updateFromDto(TrainingSkill $skill, TrainingSkillUpdateDto $dto): void
    {
        if (null !== $dto->programId) {
            $program = $this->entityManager->getRepository(TrainingProgram::class)->find($dto->programId);
            if (null === $program) {
                throw new \InvalidArgumentException('Programa no encontrado');
            }
            $skill->setProgram($program);
        }

        if (null !== $dto->levelId) {
            $level = $this->entityManager->getRepository(TrainingLevel::class)->find($dto->levelId);
            if (null === $level) {
                throw new \InvalidArgumentException('Nivel no encontrado');
            }
            $skill->setLevel($level);
        }

        if (null !== $dto->name) {
            $skill->setName($dto->name);
        }

        if (null !== $dto->family) {
            $skill->setFamily($dto->family);
        }

        if (null !== $dto->description) {
            $skill->setDescription($dto->description);
        }

        if (null !== $dto->icon) {
            $skill->setIcon($dto->icon);
        }

        if (null !== $dto->unlockAtLevel) {
            $skill->setUnlockAtLevel($dto->unlockAtLevel);
        }

        if (null !== $dto->masteryAtLevel) {
            $skill->setMasteryAtLevel($dto->masteryAtLevel);
        }

        if (null !== $dto->videoTutorialUrl) {
            $skill->setVideoTutorialUrl($dto->videoTutorialUrl);
        }

        if (null !== $dto->difficultyScore) {
            $skill->setDifficultyScore($dto->difficultyScore);
        }

        if (null !== $dto->isKeySkill) {
            $skill->setIsKeySkill($dto->isKeySkill);
        }
    }
}
