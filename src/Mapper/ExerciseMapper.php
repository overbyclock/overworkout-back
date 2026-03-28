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
        if (null !== $dto->secondaryMuscleGroup) {
            $exercise->setSecondaryMuscleGroup($dto->getSecondaryMuscleGroupEnum());
        }
        $exercise->setLevel($dto->getLevelEnum());
        $exercise->setMedia($dto->media);
        $exercise->setDifficultyRating($dto->difficultyRating);
        $exercise->setDescription($dto->description);
        $exercise->setDisciplines($dto->disciplines ?? ['calisthenics']);

        if (null !== $dto->equipmentId) {
            $equipment = $this->entityManager->getRepository(Equipments::class)->find($dto->equipmentId);
            if (null === $equipment) {
                throw new \InvalidArgumentException('Equipment not found: '.$dto->equipmentId);
            }
            $exercise->setEquipment($equipment);
        }

        return $exercise;
    }

    public function updateFromDto(Exercises $exercise, ExerciseUpdateDto $dto): void
    {
        if (null !== $dto->name) {
            $exercise->setName($dto->name);
        }

        if (null !== $dto->primaryMuscleGroup) {
            $exercise->setPrimaryMuscleGroup($dto->getPrimaryMuscleGroupEnum());
        }

        if (null !== $dto->secondaryMuscleGroup) {
            $exercise->setSecondaryMuscleGroup($dto->getSecondaryMuscleGroupEnum());
        }

        if (null !== $dto->level) {
            $exercise->setLevel($dto->getLevelEnum());
        }

        if (null !== $dto->media) {
            $exercise->setMedia($dto->media);
        }

        if (null !== $dto->difficultyRating) {
            $exercise->setDifficultyRating($dto->difficultyRating);
        }

        if (null !== $dto->description) {
            $exercise->setDescription($dto->description);
        }

        if (null !== $dto->disciplines) {
            $exercise->setDisciplines($dto->disciplines);
        }

        if (null !== $dto->equipmentId) {
            $equipment = $this->entityManager->getRepository(Equipments::class)->find($dto->equipmentId);
            if (null === $equipment) {
                throw new \InvalidArgumentException('Equipment not found: '.$dto->equipmentId);
            }
            $exercise->setEquipment($equipment);
        }
    }
}
