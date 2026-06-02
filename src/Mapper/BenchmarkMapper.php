<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\Request\BenchmarkCreateDto;
use App\Dto\Request\BenchmarkUpdateDto;
use App\Entity\Benchmark;

readonly class BenchmarkMapper
{
    public function fromCreateDto(BenchmarkCreateDto $dto): Benchmark
    {
        $benchmark = new Benchmark();
        $benchmark->setName($dto->name);
        $benchmark->setType($dto->type);
        $benchmark->setDescription($dto->description);
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
    }
}
