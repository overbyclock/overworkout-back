<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\Request\TrainingCreateDto;
use App\Dto\Request\TrainingExerciseConfigDto;
use App\Dto\Request\TrainingRoundDto;
use App\Dto\Request\TrainingUpdateDto;
use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingRound;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class TrainingMapper
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function fromCreateDto(TrainingCreateDto $dto, User $user): Training
    {
        $training = new Training();
        $training->setDiscipline($dto->getDisciplineEnum());
        $training->setTarget($dto->getTargetEnum());
        $training->setName($dto->name);

        foreach ($dto->rounds as $roundDto) {
            $round = $this->createRoundFromDto($roundDto);
            $training->addTrainingRound($round);
        }

        return $training;
    }

    public function updateFromDto(Training $training, TrainingUpdateDto $dto): void
    {
        if (null !== $dto->discipline) {
            $training->setDiscipline($dto->getDisciplineEnum());
        }

        if (null !== $dto->target) {
            $training->setTarget($dto->getTargetEnum());
        }

        if (null !== $dto->name) {
            $training->setName($dto->name);
        }

        if (null !== $dto->rounds) {
            $this->updateRounds($training, $dto->rounds);
        }
    }

    private function createRoundFromDto(TrainingRoundDto $dto): TrainingRound
    {
        $round = new TrainingRound();
        $round->setRound($dto->round);
        $round->setRestBetweenRounds($dto->restBetweenRounds);

        foreach ($dto->exercises as $exerciseDto) {
            $exerciseConfig = $this->createExerciseConfigFromDto($exerciseDto);
            $round->addTrainingExerciseConfiguration($exerciseConfig);
        }

        return $round;
    }

    private function createExerciseConfigFromDto(TrainingExerciseConfigDto $dto): TrainingExerciseConfiguration
    {
        $exercise = $this->entityManager->getRepository(Exercises::class)->find($dto->exerciseId);

        if (null === $exercise) {
            throw new \InvalidArgumentException('Exercise not found: '.$dto->exerciseId);
        }

        $config = new TrainingExerciseConfiguration();
        $config->setExercise($exercise);
        $config->setReps($dto->reps);
        $config->setSets($dto->sets ?? 1);
        $config->setRestBetweenSets($dto->restBetweenSets ?? 30);
        $config->setRestBetweenExercises($dto->restBetweenExercises ?? 15);
        $config->setMaxTimeForReps($dto->maxTimeForReps);
        $config->setWeight(null !== $dto->weight ? (int) $dto->weight : null);

        return $config;
    }

    /**
     * @param TrainingRoundDto[] $roundDtos
     */
    private function updateRounds(Training $training, array $roundDtos): void
    {
        foreach ($roundDtos as $roundDto) {
            if (null !== $roundDto->id) {
                // Actualizar round existente
                $round = $this->entityManager->getRepository(TrainingRound::class)->find($roundDto->id);
                if ($round && $round->getTraining() === $training) {
                    $round->setRound($roundDto->round);
                    $round->setRestBetweenRounds($roundDto->restBetweenRounds);
                    $this->updateExercisesInRound($round, $roundDto->exercises);
                }
            } else {
                // Crear nuevo round
                $newRound = $this->createRoundFromDto($roundDto);
                $training->addTrainingRound($newRound);
            }
        }
    }

    /**
     * @param TrainingExerciseConfigDto[] $exerciseDtos
     */
    private function updateExercisesInRound(TrainingRound $round, array $exerciseDtos): void
    {
        foreach ($exerciseDtos as $exerciseDto) {
            if (null !== $exerciseDto->id) {
                // Actualizar ejercicio existente
                $config = $this->entityManager->getRepository(TrainingExerciseConfiguration::class)->find($exerciseDto->id);
                if ($config && $config->getTrainingRound() === $round) {
                    $config->setReps($exerciseDto->reps ?? $config->getReps());
                    $config->setSets($exerciseDto->sets ?? $config->getSets());
                    $config->setRestBetweenSets($exerciseDto->restBetweenSets ?? $config->getRestBetweenSets());
                    $config->setRestBetweenExercises($exerciseDto->restBetweenExercises ?? $config->getRestBetweenExercises());
                    $config->setMaxTimeForReps($exerciseDto->maxTimeForReps ?? $config->getMaxTimeForReps());
                    $config->setWeight(null !== $exerciseDto->weight ? (int) $exerciseDto->weight : $config->getWeight());
                }
            } else {
                // Crear nuevo ejercicio
                $newConfig = $this->createExerciseConfigFromDto($exerciseDto);
                $round->addTrainingExerciseConfiguration($newConfig);
            }
        }
    }
}
