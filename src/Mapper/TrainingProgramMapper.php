<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\Request\TrainingProgramCreateDto;
use App\Dto\Request\TrainingProgramUpdateDto;
use App\Entity\TrainingProgram;

readonly class TrainingProgramMapper
{
    public function fromCreateDto(TrainingProgramCreateDto $dto): TrainingProgram
    {
        $program = new TrainingProgram();
        $program->setName($dto->name);
        $program->setSlug($dto->slug);
        $program->setDiscipline($dto->discipline);
        $program->setDifficulty($dto->difficulty);
        $program->setTotalLevels($dto->totalLevels);
        $program->setEstimatedDurationWeeks($dto->estimatedDurationWeeks);
        $program->setImageUrl($dto->imageUrl);
        $program->setDescription($dto->description);
        $program->setIsActive($dto->isActive);

        return $program;
    }

    public function updateFromDto(TrainingProgram $program, TrainingProgramUpdateDto $dto): void
    {
        if (null !== $dto->name) {
            $program->setName($dto->name);
        }

        if (null !== $dto->slug) {
            $program->setSlug($dto->slug);
        }

        if (null !== $dto->discipline) {
            $program->setDiscipline($dto->discipline);
        }

        if (null !== $dto->difficulty) {
            $program->setDifficulty($dto->difficulty);
        }

        if (null !== $dto->totalLevels) {
            $program->setTotalLevels($dto->totalLevels);
        }

        if (null !== $dto->estimatedDurationWeeks) {
            $program->setEstimatedDurationWeeks($dto->estimatedDurationWeeks);
        }

        if (null !== $dto->imageUrl) {
            $program->setImageUrl($dto->imageUrl);
        }

        if (null !== $dto->description) {
            $program->setDescription($dto->description);
        }

        if (null !== $dto->isActive) {
            $program->setIsActive($dto->isActive);
        }
    }
}
