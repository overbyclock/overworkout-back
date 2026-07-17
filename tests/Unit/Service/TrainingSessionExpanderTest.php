<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingRound;
use App\Entity\UserTrainingLog;
use App\Entity\UserTrainingSessionStep;
use App\Service\TrainingSessionExpander;
use PHPUnit\Framework\TestCase;

final class TrainingSessionExpanderTest extends TestCase
{
    private TrainingSessionExpander $expander;

    protected function setUp(): void
    {
        $this->expander = new TrainingSessionExpander();
    }

    public function testClassicBlockWithSetsExpandsSetsAndRests(): void
    {
        // 1 bloque (1 vuelta), 2 ejercicios de 3 series por reps
        $training = $this->createTraining([
            $this->createRound(1, 1, null, [
                $this->createConfig('Wall Push Up', reps: 10, sets: 3, restSets: 90, restEx: 45),
                $this->createConfig('Knee Push Up', reps: 10, sets: 3, restSets: 90, restEx: 45),
            ]),
        ]);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        $steps = $log->getSessionSteps()->toArray();
        $this->assertCount(11, $steps);

        $expected = [
            [UserTrainingSessionStep::TYPE_EXERCISE, 'Wall Push Up', 1],
            [UserTrainingSessionStep::TYPE_REST, null, null],
            [UserTrainingSessionStep::TYPE_EXERCISE, 'Wall Push Up', 2],
            [UserTrainingSessionStep::TYPE_REST, null, null],
            [UserTrainingSessionStep::TYPE_EXERCISE, 'Wall Push Up', 3],
            [UserTrainingSessionStep::TYPE_REST, null, null], // between_exercises 45s
            [UserTrainingSessionStep::TYPE_EXERCISE, 'Knee Push Up', 1],
            [UserTrainingSessionStep::TYPE_REST, null, null],
            [UserTrainingSessionStep::TYPE_EXERCISE, 'Knee Push Up', 2],
            [UserTrainingSessionStep::TYPE_REST, null, null],
            [UserTrainingSessionStep::TYPE_EXERCISE, 'Knee Push Up', 3],
        ];

        foreach ($expected as $i => [$type, $name, $setNumber]) {
            $this->assertSame($type, $steps[$i]->getType(), "Paso $i");
            $this->assertSame($i, $steps[$i]->getPosition(), "Posición del paso $i");
            if (null !== $name) {
                $this->assertSame($name, $steps[$i]->getPayload()['exerciseName']);
                $this->assertSame($setNumber, $steps[$i]->getPayload()['setNumber']);
                $this->assertSame(3, $steps[$i]->getPayload()['totalSets']);
            }
        }

        // El descanso entre series es de 90s y entre ejercicios de 45s; no hay rest final
        $this->assertSame(90, $steps[1]->getPayload()['restSeconds']);
        $this->assertSame('between_sets', $steps[1]->getPayload()['reason']);
        $this->assertSame(45, $steps[5]->getPayload()['restSeconds']);
        $this->assertSame('between_exercises', $steps[5]->getPayload()['reason']);
        $this->assertSame('Knee Push Up', $steps[5]->getPayload()['nextExerciseName']);
        $this->assertSame(UserTrainingSessionStep::TYPE_EXERCISE, $steps[10]->getType());
    }

    public function testCircuitExpandsLapsWithRestBetweenLaps(): void
    {
        // Circuito: 3 vueltas, 2 ejercicios de 1 serie
        $training = $this->createTraining([
            $this->createRound(3, 60, null, [
                $this->createConfig('Jumping Jacks', reps: 20, sets: 1, restSets: 30, restEx: 30),
                $this->createConfig('Squats', reps: 15, sets: 1, restSets: 30, restEx: 30),
            ]),
        ], isCircuit: true);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        $steps = $log->getSessionSteps()->toArray();
        // Vuelta: E1, R30, E2, (R60 entre vueltas) x3, sin rest final
        $this->assertCount(11, $steps);
        $this->assertSame('between_laps', $steps[3]->getPayload()['reason']);
        $this->assertSame(60, $steps[3]->getPayload()['restSeconds']);
        $this->assertSame('between_laps', $steps[7]->getPayload()['reason']);
        $this->assertSame(2, $steps[4]->getPayload()['lapNumber']);
        $this->assertSame(3, $steps[4]->getPayload()['totalLaps']);
        $this->assertSame(UserTrainingSessionStep::TYPE_EXERCISE, $steps[10]->getType());
    }

    public function testRepeatedBlockEmitsRestBetweenLaps(): void
    {
        // No circuito: bloque de 1 serie por ejercicio repetido 2 veces
        $training = $this->createTraining([
            $this->createRound(2, 90, null, [
                $this->createConfig('Frog Stand', maxTime: 20, sets: 1, restSets: 30, restEx: 30),
                $this->createConfig('Hollow Hold', maxTime: 25, sets: 1, restSets: 30, restEx: 30),
            ]),
        ]);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        $steps = $log->getSessionSteps()->toArray();
        $this->assertCount(7, $steps);
        $this->assertSame('between_laps', $steps[3]->getPayload()['reason']);
        $this->assertSame(90, $steps[3]->getPayload()['restSeconds']);
    }

    public function testMultipleBlocksEmitRestAfterBlock(): void
    {
        $training = $this->createTraining([
            $this->createRound(1, 1, 120, [
                $this->createConfig('Push Up', reps: 10, sets: 1, restSets: 60, restEx: 30),
            ]),
            $this->createRound(1, 1, null, [
                $this->createConfig('Plank', maxTime: 30, sets: 1, restSets: 60, restEx: 30),
            ]),
        ]);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        $steps = $log->getSessionSteps()->toArray();
        $this->assertCount(3, $steps);
        $this->assertSame('after_block', $steps[1]->getPayload()['reason']);
        $this->assertSame(120, $steps[1]->getPayload()['restSeconds']);
        $this->assertSame('Plank', $steps[1]->getPayload()['nextExerciseName']);
        $this->assertSame(2, $steps[2]->getPayload()['blockNumber']);
        $this->assertSame(2, $steps[2]->getPayload()['totalBlocks']);
    }

    public function testTimeBasedExerciseCarriesDurationAndNoReps(): void
    {
        $training = $this->createTraining([
            $this->createRound(1, 1, null, [
                $this->createConfig('High Plank', maxTime: 30, sets: 1, restSets: 90, restEx: 45),
            ]),
        ]);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        $steps = $log->getSessionSteps()->toArray();
        $this->assertCount(1, $steps);
        $payload = $steps[0]->getPayload();
        $this->assertSame(30, $payload['durationSeconds']);
        $this->assertNull($payload['reps']);
    }

    public function testNullRestBetweenExercisesEmitsNoRest(): void
    {
        $training = $this->createTraining([
            $this->createRound(1, 1, null, [
                $this->createConfig('Push Up', reps: 10, sets: 1, restSets: 60, restEx: null),
                $this->createConfig('Squat', reps: 10, sets: 1, restSets: 60, restEx: 30),
            ]),
        ]);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        $steps = $log->getSessionSteps()->toArray();
        $this->assertCount(2, $steps);
        $this->assertSame(UserTrainingSessionStep::TYPE_EXERCISE, $steps[0]->getType());
        $this->assertSame(UserTrainingSessionStep::TYPE_EXERCISE, $steps[1]->getType());
    }

    public function testEmptyTrainingThrowsException(): void
    {
        $training = new Training();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('El entrenamiento no tiene contenido.');

        $this->expander->expand($training, new UserTrainingLog());
    }

    /**
     * @param TrainingRound[] $rounds
     */
    private function createTraining(array $rounds, bool $isCircuit = false): Training
    {
        $training = new Training();
        $training->setIsCircuit($isCircuit);
        foreach ($rounds as $round) {
            $training->addTrainingRound($round);
        }

        return $training;
    }

    /**
     * @param TrainingExerciseConfiguration[] $configs
     */
    private function createRound(int $setsForRound, int $restBetweenRounds, ?int $restAfterBlock, array $configs): TrainingRound
    {
        $round = new TrainingRound();
        $round->setSetsForRound($setsForRound);
        $round->setRestBetweenRounds($restBetweenRounds);
        $round->setRestAfterBlock($restAfterBlock);
        foreach ($configs as $config) {
            $round->addTrainingExerciseConfiguration($config);
        }

        return $round;
    }

    private function createConfig(string $exerciseName, ?int $reps = null, ?int $maxTime = null, int $sets = 1, int $restSets = 60, ?int $restEx = 30): TrainingExerciseConfiguration
    {
        $exercise = new Exercises();
        $exercise->setName($exerciseName);

        $config = new TrainingExerciseConfiguration();
        $config->setExercise($exercise);
        $config->setReps($reps);
        $config->setMaxTimeForReps($maxTime);
        $config->setSets($sets);
        $config->setRestBetweenSets($restSets);
        $config->setRestBetweenExercises($restEx);

        return $config;
    }
}
