<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingRound;

/**
 * Calcula el tiempo estimado de un entrenamiento en segundos.
 *
 * Soporta tanto circuitos (varias rondas de ejercicios) como
 * entrenamientos tradicionales (sets seguidos por ejercicio).
 */
class TrainingTimeCalculator
{
    private const SECONDS_PER_REP_MIN = 3;
    private const SECONDS_PER_REP_MAX = 5;
    private const FALLBACK_EXERCISE_TIME_MIN = 30;
    private const FALLBACK_EXERCISE_TIME_MAX = 45;

    /**
     * @return array{min: int, max: int} Tiempo total en segundos
     */
    public function calculate(Training $training): array
    {
        $rounds = $training->getTrainingRounds();

        if ($rounds->isEmpty()) {
            return ['min' => 0, 'max' => 0];
        }

        $isCircuit = $training->isCircuit() ?? false;
        $totalMin = 0;
        $totalMax = 0;

        /** @var TrainingRound $round */
        foreach ($rounds as $roundIndex => $round) {
            $roundResult = $isCircuit
                ? $this->calculateCircuitRound($round)
                : $this->calculateTraditionalRound($round);

            $totalMin += $roundResult['min'];
            $totalMax += $roundResult['max'];

            // Descanso entre rounds del training (si hay múltiples TrainingRounds)
            if ($roundIndex < $rounds->count() - 1) {
                $rest = $round->getRestBetweenRounds() ?? 0;
                $totalMin += $rest;
                $totalMax += $rest;
            }
        }

        return [
            'min' => (int) $totalMin,
            'max' => (int) $totalMax,
        ];
    }

    /**
     * @return array{min: int, max: int}
     */
    private function calculateCircuitRound(TrainingRound $round): array
    {
        $configs = $round->getTrainingExerciseConfigurations();
        if ($configs->isEmpty()) {
            return ['min' => 0, 'max' => 0];
        }

        $numberOfRounds = $round->getSetsForRound() ?? 1;
        $restBetweenExercises = $this->getRestBetweenExercisesForRound($round);
        $restBetweenRounds = $round->getRestBetweenRounds() ?? 0;

        $timePerRoundMin = 0;
        $timePerRoundMax = 0;

        /** @var TrainingExerciseConfiguration $config */
        foreach ($configs as $index => $config) {
            $exTime = $this->calculateExerciseTime($config);
            $timePerRoundMin += $exTime['min'];
            $timePerRoundMax += $exTime['max'];

            // Descanso entre ejercicios (excepto después del último)
            if ($index < $configs->count() - 1) {
                $timePerRoundMin += $restBetweenExercises;
                $timePerRoundMax += $restBetweenExercises;
            }
        }

        $totalMin = 0;
        $totalMax = 0;

        for ($i = 0; $i < $numberOfRounds; ++$i) {
            $totalMin += $timePerRoundMin;
            $totalMax += $timePerRoundMax;

            // Descanso entre rondas (excepto después de la última)
            if ($i < $numberOfRounds - 1) {
                $totalMin += $restBetweenRounds;
                $totalMax += $restBetweenRounds;
            }
        }

        return [
            'min' => (int) $totalMin,
            'max' => (int) $totalMax,
        ];
    }

    /**
     * @return array{min: int, max: int}
     */
    private function calculateTraditionalRound(TrainingRound $round): array
    {
        $configs = $round->getTrainingExerciseConfigurations();
        if ($configs->isEmpty()) {
            return ['min' => 0, 'max' => 0];
        }

        $restBetweenExercises = $this->getRestBetweenExercisesForRound($round);
        $totalMin = 0;
        $totalMax = 0;

        /** @var TrainingExerciseConfiguration $config */
        foreach ($configs as $index => $config) {
            $exTime = $this->calculateExerciseTime($config);
            $sets = $config->getSets() ?? 1;
            $restBetweenSets = $config->getRestBetweenSets() ?? 0;

            for ($s = 0; $s < $sets; ++$s) {
                $totalMin += $exTime['min'];
                $totalMax += $exTime['max'];

                // Descanso entre sets (excepto después del último)
                if ($s < $sets - 1) {
                    $totalMin += $restBetweenSets;
                    $totalMax += $restBetweenSets;
                }
            }

            // Descanso entre ejercicios (excepto después del último)
            if ($index < $configs->count() - 1) {
                $totalMin += $restBetweenExercises;
                $totalMax += $restBetweenExercises;
            }
        }

        return [
            'min' => (int) $totalMin,
            'max' => (int) $totalMax,
        ];
    }

    /**
     * @return array{min: int, max: int}
     */
    private function calculateExerciseTime(TrainingExerciseConfiguration $config): array
    {
        $maxTime = $config->getMaxTimeForReps();

        if (null !== $maxTime && $maxTime > 0) {
            return ['min' => $maxTime, 'max' => $maxTime];
        }

        $reps = $config->getReps();

        if (null !== $reps && $reps > 0) {
            return [
                'min' => $reps * self::SECONDS_PER_REP_MIN,
                'max' => $reps * self::SECONDS_PER_REP_MAX,
            ];
        }

        return [
            'min' => self::FALLBACK_EXERCISE_TIME_MIN,
            'max' => self::FALLBACK_EXERCISE_TIME_MAX,
        ];
    }

    private function getRestBetweenExercisesForRound(TrainingRound $round): int
    {
        $configs = $round->getTrainingExerciseConfigurations();
        if ($configs->isEmpty()) {
            return 0;
        }

        $firstConfig = $configs->first();

        return $firstConfig->getRestBetweenExercises()
            ?? $firstConfig->getRestBetweenSets()
            ?? 0;
    }
}
