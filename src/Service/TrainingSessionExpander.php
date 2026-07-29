<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingRound;
use App\Entity\UserTrainingLog;
use App\Entity\UserTrainingSessionStep;

/**
 * Expande un entrenamiento en una lista plana y ordenada de pasos
 * (ejercicios y descansos) persistida como snapshot de la sesión.
 */
readonly class TrainingSessionExpander
{
    /**
     * @throws \InvalidArgumentException si el entrenamiento no tiene contenido
     */
    public function expand(Training $training, UserTrainingLog $log): void
    {
        $rounds = $training->getTrainingRounds()->toArray();

        if ([] === $rounds) {
            throw new \InvalidArgumentException('El entrenamiento no tiene contenido.');
        }

        $position = 0;
        $totalBlocks = \count($rounds);

        foreach ($rounds as $blockIndex => $round) {
            $configs = $round->getTrainingExerciseConfigurations()->toArray();

            if ([] === $configs) {
                continue;
            }

            $laps = max(1, $round->getSetsForRound() ?? 1);
            $lastConfigIndex = \count($configs) - 1;

            for ($lap = 1; $lap <= $laps; ++$lap) {
                foreach ($configs as $configIndex => $config) {
                    $sets = max(1, $config->getSets() ?? 1);

                    for ($set = 1; $set <= $sets; ++$set) {
                        $log->addSessionStep($this->createExerciseStep($position++, $config, $set, $sets, $lap, $laps, $blockIndex + 1, $totalBlocks, $round->getBlockType(), $round->getName(), $round->getDescription()));

                        if ($set < $sets && null !== $config->getRestBetweenSets()) {
                            $log->addSessionStep($this->createRestStep($position++, $config->getRestBetweenSets(), 'between_sets', $this->exerciseName($config)));
                        }
                    }

                    if ($configIndex < $lastConfigIndex && null !== $config->getRestBetweenExercises()) {
                        $log->addSessionStep($this->createRestStep($position++, $config->getRestBetweenExercises(), 'between_exercises', $this->exerciseName($configs[$configIndex + 1])));
                    }
                }

                if ($lap < $laps) {
                    $log->addSessionStep($this->createRestStep($position++, $round->getRestBetweenRounds() ?? 60, 'between_laps', $this->exerciseName($configs[0])));
                }
            }

            $hasNextBlock = $blockIndex < $totalBlocks - 1;
            if ($hasNextBlock && null !== $round->getRestAfterBlock()) {
                $nextRound = $rounds[$blockIndex + 1];
                $log->addSessionStep($this->createRestStep(
                    $position++,
                    $round->getRestAfterBlock(),
                    'after_block',
                    $this->firstExerciseName($nextRound),
                    [
                        'nextBlockNumber' => $blockIndex + 2,
                        'nextBlockType' => $nextRound->getBlockType(),
                        'nextBlockName' => $nextRound->getName(),
                        'nextBlockDescription' => $nextRound->getDescription(),
                    ],
                ));
            }
        }

        if (0 === $position) {
            throw new \InvalidArgumentException('El entrenamiento no tiene contenido.');
        }
    }

    private function createExerciseStep(int $position, TrainingExerciseConfiguration $config, int $set, int $sets, int $lap, int $laps, int $block, int $totalBlocks, ?string $blockType = null, ?string $blockName = null, ?string $blockDescription = null): UserTrainingSessionStep
    {
        $exercise = $config->getExercise();

        $step = new UserTrainingSessionStep();
        $step->setPosition($position);
        $step->setType(UserTrainingSessionStep::TYPE_EXERCISE);
        $step->setPayload([
            'exerciseId' => $exercise?->getId(),
            'exerciseName' => $exercise?->getName() ?? 'Ejercicio',
            'media' => $exercise?->getMedia(),
            'setNumber' => $set,
            'totalSets' => $sets,
            'reps' => $config->getReps(),
            'durationSeconds' => $config->getMaxTimeForReps(),
            'notes' => $config->getNotes(),
            'lapNumber' => $lap,
            'totalLaps' => $laps,
            'blockNumber' => $block,
            'totalBlocks' => $totalBlocks,
            'blockType' => $blockType,
            'blockName' => $blockName,
            'blockDescription' => $blockDescription,
        ]);

        return $step;
    }

    /**
     * @param array<string, mixed> $extraPayload metadatos adicionales (p. ej. bloque destino en descansos after_block)
     */
    private function createRestStep(int $position, int $restSeconds, string $reason, ?string $nextExerciseName, array $extraPayload = []): UserTrainingSessionStep
    {
        $step = new UserTrainingSessionStep();
        $step->setPosition($position);
        $step->setType(UserTrainingSessionStep::TYPE_REST);
        $step->setPayload(array_merge([
            'restSeconds' => $restSeconds,
            'reason' => $reason,
            'nextExerciseName' => $nextExerciseName,
        ], $extraPayload));

        return $step;
    }

    private function exerciseName(TrainingExerciseConfiguration $config): string
    {
        return $config->getExercise()?->getName() ?? 'Ejercicio';
    }

    private function firstExerciseName(TrainingRound $round): ?string
    {
        $configs = $round->getTrainingExerciseConfigurations()->toArray();

        return [] === $configs ? null : $this->exerciseName($configs[0]);
    }
}
