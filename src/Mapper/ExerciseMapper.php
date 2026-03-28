<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\Request\ExerciseCreateDto;
use App\Dto\Request\ExerciseUpdateDto;
use App\Entity\Equipments;
use App\Entity\Exercises;
use Doctrine\ORM\EntityManagerInterface;

readonly class ExerciseMapper
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function fromCreateDto(ExerciseCreateDto $dto): Exercises
    {
        $exercise = new Exercises();
        $exercise->setName($dto->name);
        $exercise->setPrimaryMuscleGroup($dto->getPrimaryMuscleGroupEnum());
        if ($dto->secondaryMuscleGroup !== null) {
            $exercise->setSecondaryMuscleGroup($dto->getSecondaryMuscleGroupEnum());
        }
        $exercise->setLevel($dto->getLevelEnum());
        $exercise->setMedia($dto->media);
        $exercise->setDifficultyRating($dto->difficultyRating);
        $exercise->setDescription($dto->description);
        $exercise->setDisciplines($dto->disciplines ?? ['calisthenics']);

        if ($dto->equipmentId !== null) {
            $equipment = $this->entityManager->getRepository(Equipments::class)->find($dto->equipmentId);
            if ($equipment === null) {
                throw new \InvalidArgumentException('Equipment not found: ' . $dto->equipmentId);
            }
            $exercise->setEquipment($equipment);
        }

        return $exercise;
    }

    public function updateFromDto(Exercises $exercise, ExerciseUpdateDto $dto): void
    {
        if ($dto->name !== null) {
            $exercise->setName($dto->name);
        }

        if ($dto->primaryMuscleGroup !== null) {
            $exercise->setPrimaryMuscleGroup($dto->getPrimaryMuscleGroupEnum());
        }

        if ($dto->secondaryMuscleGroup !== null) {
            $exercise->setSecondaryMuscleGroup($dto->getSecondaryMuscleGroupEnum());
        }

        if ($dto->level !== null) {
            $exercise->setLevel($dto->getLevelEnum());
        }

        if ($dto->media !== null) {
            $exercise->setMedia($dto->media);
        }

        if ($dto->difficultyRating !== null) {
            $exercise->setDifficultyRating($dto->difficultyRating);
        }

        if ($dto->description !== null) {
            $exercise->setDescription($dto->description);
        }

        if ($dto->disciplines !== null) {
            $exercise->setDisciplines($dto->disciplines);
        }

        if ($dto->equipmentId !== null) {
            $equipment = $this->entityManager->getRepository(Equipments::class)->find($dto->equipmentId);
            if ($equipment === null) {
                throw new \InvalidArgumentException('Equipment not found: ' . $dto->equipmentId);
            }
            $exercise->setEquipment($equipment);
        }
    }
}
