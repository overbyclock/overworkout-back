<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\Request\TrainingLevelCreateDto;
use App\Dto\Request\TrainingLevelUpdateDto;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use Doctrine\ORM\EntityManagerInterface;

readonly class TrainingLevelMapper
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function fromCreateDto(TrainingLevelCreateDto $dto): TrainingLevel
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($dto->programId);

        if (null === $program) {
            throw new \InvalidArgumentException('Programa no encontrado: '.$dto->programId);
        }

        $level = new TrainingLevel();
        $level->setProgram($program);
        $level->setName($dto->name);
        $level->setLevelNumber($dto->levelNumber);
        $level->setTitle($dto->title);
        $level->setDescription($dto->description);
        $level->setObjective($dto->objective);
        $level->setEstimatedDurationWeeks($dto->estimatedDurationWeeks);
        $level->setDifficultyRating($dto->difficultyRating);
        $level->setColor($dto->color);
        $level->setIcon($dto->icon);
        $level->setRequirementsSummary($dto->requirementsSummary);
        $level->setTips($dto->tips);
        $level->setTestRequirements($dto->testRequirements);
        $level->setSkillFocus($dto->skillFocus);
        $level->setProgramVersion($dto->programVersion);
        $level->setIsInfinite($dto->isInfinite);
        $level->setIsLockedByDefault($dto->isLockedByDefault);

        return $level;
    }

    public function updateFromDto(TrainingLevel $level, TrainingLevelUpdateDto $dto): void
    {
        if (null !== $dto->name) {
            $level->setName($dto->name);
        }

        if (null !== $dto->levelNumber) {
            $level->setLevelNumber($dto->levelNumber);
        }

        if (null !== $dto->title) {
            $level->setTitle($dto->title);
        }

        if (null !== $dto->description) {
            $level->setDescription($dto->description);
        }

        if (null !== $dto->objective) {
            $level->setObjective($dto->objective);
        }

        if (null !== $dto->estimatedDurationWeeks) {
            $level->setEstimatedDurationWeeks($dto->estimatedDurationWeeks);
        }

        if (null !== $dto->difficultyRating) {
            $level->setDifficultyRating($dto->difficultyRating);
        }

        if (null !== $dto->color) {
            $level->setColor($dto->color);
        }

        if (null !== $dto->icon) {
            $level->setIcon($dto->icon);
        }

        if (null !== $dto->requirementsSummary) {
            $level->setRequirementsSummary($dto->requirementsSummary);
        }

        if (null !== $dto->tips) {
            $level->setTips($dto->tips);
        }

        if (null !== $dto->testRequirements) {
            $level->setTestRequirements($dto->testRequirements);
        }

        if (null !== $dto->skillFocus) {
            $level->setSkillFocus($dto->skillFocus);
        }

        if (null !== $dto->programVersion) {
            $level->setProgramVersion($dto->programVersion);
        }

        if (null !== $dto->isInfinite) {
            $level->setIsInfinite($dto->isInfinite);
        }

        if (null !== $dto->isLockedByDefault) {
            $level->setIsLockedByDefault($dto->isLockedByDefault);
        }
    }
}
