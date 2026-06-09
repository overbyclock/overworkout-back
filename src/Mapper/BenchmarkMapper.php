<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\Request\BenchmarkCreateDto;
use App\Dto\Request\BenchmarkExerciseDto;
use App\Dto\Request\BenchmarkUpdateDto;
use App\Entity\Benchmark;
use App\Entity\BenchmarkExercise;
use App\Repository\ExercisesRepository;

readonly class BenchmarkMapper
{
    public function __construct(
        private ExercisesRepository $exercisesRepository,
    ) {
    }

    public function fromCreateDto(BenchmarkCreateDto $dto): Benchmark
    {
        $benchmark = new Benchmark();
        $benchmark->setName($dto->name);
        $benchmark->setType($dto->type);
        $benchmark->setDescription($dto->description);
        $benchmark->setFormat($dto->format);
        $benchmark->setRounds($dto->rounds);
        $benchmark->setTimeCapSeconds($dto->timeCapSeconds);
        $benchmark->setRestBetweenRoundsSeconds($dto->restBetweenRoundsSeconds);
        $benchmark->setPartitioningAllowed($dto->partitioningAllowed);
        $benchmark->setTimerConfig($dto->timerConfig);
        $benchmark->setRxWeightMale($dto->rxWeightMale);
        $benchmark->setRxWeightFemale($dto->rxWeightFemale);
        $benchmark->setMovements($dto->movements);
        $benchmark->setEliteTimeMale($dto->eliteTimeMale);
        $benchmark->setEliteTimeFemale($dto->eliteTimeFemale);
        $benchmark->setAdvancedTimeMale($dto->advancedTimeMale);
        $benchmark->setAdvancedTimeFemale($dto->advancedTimeFemale);
        $benchmark->setIntermediateTimeMale($dto->intermediateTimeMale);
        $benchmark->setIntermediateTimeFemale($dto->intermediateTimeFemale);
        $benchmark->setBeginnerTimeMale($dto->beginnerTimeMale);
        $benchmark->setBeginnerTimeFemale($dto->beginnerTimeFemale);
        $benchmark->setScalingOptions($dto->scalingOptions);
        $benchmark->setVideoUrl($dto->videoUrl);

        if (null !== $dto->exercises) {
            foreach ($dto->exercises as $exerciseDto) {
                $benchmark->addExercise($this->createBenchmarkExercise($exerciseDto));
            }
        }

        return $benchmark;
    }

    public function updateFromDto(Benchmark $benchmark, BenchmarkUpdateDto $dto): void
    {
        if (null !== $dto->name) {
            $benchmark->setName($dto->name);
        }

        if (null !== $dto->type) {
            $benchmark->setType($dto->type);
        }

        if (null !== $dto->description) {
            $benchmark->setDescription($dto->description);
        }

        if (null !== $dto->format) {
            $benchmark->setFormat($dto->format);
        }

        if (null !== $dto->rounds) {
            $benchmark->setRounds($dto->rounds);
        }

        if (null !== $dto->timeCapSeconds) {
            $benchmark->setTimeCapSeconds($dto->timeCapSeconds);
        }

        if (null !== $dto->restBetweenRoundsSeconds) {
            $benchmark->setRestBetweenRoundsSeconds($dto->restBetweenRoundsSeconds);
        }

        if (null !== $dto->partitioningAllowed) {
            $benchmark->setPartitioningAllowed($dto->partitioningAllowed);
        }

        if (null !== $dto->timerConfig) {
            $benchmark->setTimerConfig($dto->timerConfig);
        }

        if (null !== $dto->rxWeightMale) {
            $benchmark->setRxWeightMale($dto->rxWeightMale);
        }

        if (null !== $dto->rxWeightFemale) {
            $benchmark->setRxWeightFemale($dto->rxWeightFemale);
        }

        if (null !== $dto->movements) {
            $benchmark->setMovements($dto->movements);
        }

        if (null !== $dto->eliteTimeMale) {
            $benchmark->setEliteTimeMale($dto->eliteTimeMale);
        }

        if (null !== $dto->eliteTimeFemale) {
            $benchmark->setEliteTimeFemale($dto->eliteTimeFemale);
        }

        if (null !== $dto->advancedTimeMale) {
            $benchmark->setAdvancedTimeMale($dto->advancedTimeMale);
        }

        if (null !== $dto->advancedTimeFemale) {
            $benchmark->setAdvancedTimeFemale($dto->advancedTimeFemale);
        }

        if (null !== $dto->intermediateTimeMale) {
            $benchmark->setIntermediateTimeMale($dto->intermediateTimeMale);
        }

        if (null !== $dto->intermediateTimeFemale) {
            $benchmark->setIntermediateTimeFemale($dto->intermediateTimeFemale);
        }

        if (null !== $dto->beginnerTimeMale) {
            $benchmark->setBeginnerTimeMale($dto->beginnerTimeMale);
        }

        if (null !== $dto->beginnerTimeFemale) {
            $benchmark->setBeginnerTimeFemale($dto->beginnerTimeFemale);
        }

        if (null !== $dto->scalingOptions) {
            $benchmark->setScalingOptions($dto->scalingOptions);
        }

        if (null !== $dto->videoUrl) {
            $benchmark->setVideoUrl($dto->videoUrl);
        }

        if (null !== $dto->exercises) {
            $benchmark->clearExercises();
            foreach ($dto->exercises as $exerciseDto) {
                $benchmark->addExercise($this->createBenchmarkExercise($exerciseDto));
            }
        }
    }

    private function createBenchmarkExercise(BenchmarkExerciseDto $dto): BenchmarkExercise
    {
        $exercise = $this->exercisesRepository->find($dto->exerciseId);
        if (null === $exercise) {
            throw new \InvalidArgumentException('Ejercicio no encontrado: '.$dto->exerciseId);
        }

        $benchmarkExercise = new BenchmarkExercise();
        $benchmarkExercise->setExercise($exercise);
        $benchmarkExercise->setPosition($dto->position);
        $benchmarkExercise->setReps($dto->reps);
        $benchmarkExercise->setDistanceMeters($dto->distanceMeters);
        $benchmarkExercise->setDurationSeconds($dto->durationSeconds);
        $benchmarkExercise->setCalories($dto->calories);
        $benchmarkExercise->setRxWeightMale($dto->rxWeightMale);
        $benchmarkExercise->setRxWeightFemale($dto->rxWeightFemale);
        $benchmarkExercise->setRestSeconds($dto->restSeconds);

        return $benchmarkExercise;
    }
}
