<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Benchmark;
use App\Entity\BenchmarkExercise;
use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingRound;
use App\Entity\UserTrainingLog;
use App\Entity\UserTrainingSessionStep;
use App\Enum\BenchmarkFormat;
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

    public function testExerciseStepIncluyeDescripcionDelBloque(): void
    {
        $log = $this->expandTrainingConBloquesV4();

        $exerciseSteps = array_values(array_filter(
            $log->getSessionSteps()->toArray(),
            static fn (UserTrainingSessionStep $s): bool => UserTrainingSessionStep::TYPE_EXERCISE === $s->getType(),
        ));

        $this->assertSame('Preparación y movilidad', $exerciseSteps[0]->getPayload()['blockName']);
        $this->assertSame('Movilidad fluida encadenada', $exerciseSteps[0]->getPayload()['blockDescription']);
    }

    public function testDescansoAfterBlockIncluyeMetadatosDelBloqueDestino(): void
    {
        $log = $this->expandTrainingConBloquesV4();

        $restSteps = array_values(array_filter(
            $log->getSessionSteps()->toArray(),
            static fn (UserTrainingSessionStep $s): bool => UserTrainingSessionStep::TYPE_REST === $s->getType()
                && 'after_block' === $s->getPayload()['reason'],
        ));

        $this->assertCount(1, $restSteps);
        $payload = $restSteps[0]->getPayload();
        $this->assertSame(60, $payload['restSeconds']);
        $this->assertSame('Flexiones', $payload['nextExerciseName']);
        $this->assertSame(2, $payload['nextBlockNumber']);
        $this->assertSame('principal', $payload['nextBlockType']);
        $this->assertSame('Fuerza — Empuje', $payload['nextBlockName']);
        $this->assertSame('Trabajo de empuje del nivel', $payload['nextBlockDescription']);
    }

    public function testBloqueSinDescripcionDevuelveNull(): void
    {
        $training = new Training();
        $round = (new TrainingRound())->setRound(1)->setSetsForRound(1);
        $config = (new TrainingExerciseConfiguration())->setSets(1)->setReps(5);
        $round->addTrainingExerciseConfiguration($config);
        $training->addTrainingRound($round);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        $payload = $log->getSessionSteps()->first()->getPayload();
        $this->assertNull($payload['blockType']);
        $this->assertNull($payload['blockName']);
        $this->assertNull($payload['blockDescription']);
    }

    public function testRoundConBenchmarkEmiteUnUnicoPasoBenchmark(): void
    {
        // 5 bloques: movilidad, fuerza, WOD benchmark (sin configs), postural, descarga
        $benchmark = $this->createBenchmark();
        $training = $this->createTraining([
            $this->createRound(1, 1, null, [
                $this->createConfig('Movilidad Hombro', maxTime: 40, sets: 1),
            ]),
            $this->createRound(1, 1, null, [
                $this->createConfig('Flexiones', reps: 8, sets: 1),
            ]),
            (new TrainingRound())
                ->setSetsForRound(1)
                ->setRestBetweenRounds(1)
                ->setRestAfterBlock(90)
                ->setBlockType('principal')
                ->setName('WOD')
                ->setDescription('Test del día')
                ->setBenchmark($benchmark),
            $this->createRound(1, 1, null, [
                $this->createConfig('Plancha', maxTime: 30, sets: 1),
            ]),
            $this->createRound(1, 1, null, [
                $this->createConfig('Estiramiento', maxTime: 60, sets: 1),
            ]),
        ]);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        $steps = $log->getSessionSteps()->toArray();
        // 4 pasos exercise (1 por bloque con configs) + 1 benchmark + 1 after_block
        $this->assertCount(6, $steps);

        $benchmarkSteps = array_values(array_filter(
            $steps,
            static fn (UserTrainingSessionStep $s): bool => UserTrainingSessionStep::TYPE_BENCHMARK === $s->getType(),
        ));
        $this->assertCount(1, $benchmarkSteps);

        $payload = $benchmarkSteps[0]->getPayload();
        $this->assertNull($payload['benchmarkId']); // entidad sin persistir
        $this->assertSame('Fran', $payload['name']);
        $this->assertSame(BenchmarkFormat::FOR_TIME, $payload['format']);
        $this->assertSame(['mode' => 'countdown'], $payload['timerConfig']);
        $this->assertSame(600, $payload['timeCapSeconds']);
        $this->assertNull($payload['rounds']);
        $this->assertSame(60, $payload['restBetweenRoundsSeconds']);
        $this->assertSame('61 kg', $payload['rxWeightMale']);
        $this->assertSame('43 kg', $payload['rxWeightFemale']);
        $this->assertSame('Bajar carga si es necesario', $payload['scalingOptions']);
        $this->assertFalse($payload['partitioningAllowed']);

        $this->assertSame(3, $payload['blockNumber']);
        $this->assertSame(5, $payload['totalBlocks']);
        $this->assertSame('principal', $payload['blockType']);
        $this->assertSame('WOD', $payload['blockName']);
        $this->assertSame('Test del día', $payload['blockDescription']);

        // Ejercicios expandidos ordenados por posición con repsPattern incluido
        $this->assertCount(2, $payload['exercises']);
        $this->assertArrayHasKey('benchmarkExerciseId', $payload['exercises'][0]); // null: entidad sin persistir
        $this->assertSame('Thruster', $payload['exercises'][0]['exerciseName']);
        $this->assertSame(21, $payload['exercises'][0]['reps']);
        $this->assertSame('21-15-9', $payload['exercises'][0]['repsPattern']);
        $this->assertSame('61 kg', $payload['exercises'][0]['rxWeightMale']);
        $this->assertSame('Pull Up', $payload['exercises'][1]['exerciseName']);
        $this->assertSame(21, $payload['exercises'][1]['reps']);
        $this->assertNull($payload['exercises'][1]['rxWeightMale']);

        // El descanso tras el WOD apunta al primer ejercicio del bloque siguiente
        $this->assertSame(UserTrainingSessionStep::TYPE_REST, $steps[3]->getType());
        $this->assertSame('after_block', $steps[3]->getPayload()['reason']);
        $this->assertSame(90, $steps[3]->getPayload()['restSeconds']);
        $this->assertSame('Plancha', $steps[3]->getPayload()['nextExerciseName']);
    }

    public function testBenchmarkLadderMantieneRepsPatternIntacto(): void
    {
        $benchmark = (new Benchmark())
            ->setName('Ladder Test')
            ->setType('wod')
            ->setFormat(BenchmarkFormat::LADDER);
        $movement = (new BenchmarkExercise())
            ->setExercise((new Exercises())->setName('Wall Ball'))
            ->setPosition(0)
            ->setRepsPattern('1→10');
        $benchmark->addExercise($movement);

        $training = $this->createTraining([
            (new TrainingRound())
                ->setSetsForRound(1)
                ->setRestBetweenRounds(1)
                ->setBenchmark($benchmark),
        ]);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        $steps = $log->getSessionSteps()->toArray();
        $this->assertCount(1, $steps);
        $this->assertSame(UserTrainingSessionStep::TYPE_BENCHMARK, $steps[0]->getType());
        $payload = $steps[0]->getPayload();
        $this->assertSame(BenchmarkFormat::LADDER, $payload['format']);
        $this->assertSame('1→10', $payload['exercises'][0]['repsPattern']);
        $this->assertNull($payload['exercises'][0]['reps']);
    }

    public function testDescansoAfterBlockAntesDeRoundBenchmarkUsaNombreDelBenchmark(): void
    {
        $benchmark = (new Benchmark())->setName('Grace')->setType('wod')->setFormat(BenchmarkFormat::FOR_TIME);
        $training = $this->createTraining([
            $this->createRound(1, 1, 120, [
                $this->createConfig('Push Up', reps: 10, sets: 1),
            ]),
            (new TrainingRound())
                ->setSetsForRound(1)
                ->setRestBetweenRounds(1)
                ->setBenchmark($benchmark),
        ]);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        $steps = $log->getSessionSteps()->toArray();
        $this->assertCount(3, $steps);
        $this->assertSame('after_block', $steps[1]->getPayload()['reason']);
        $this->assertSame(120, $steps[1]->getPayload()['restSeconds']);
        $this->assertSame('Grace', $steps[1]->getPayload()['nextExerciseName']);
        $this->assertSame(UserTrainingSessionStep::TYPE_BENCHMARK, $steps[2]->getType());
    }

    /**
     * Entrenamiento v4 con dos bloques tipados (preparación + principal) y descanso after_block.
     */
    private function expandTrainingConBloquesV4(): UserTrainingLog
    {
        $training = new Training();

        $round1 = (new TrainingRound())
            ->setRound(1)
            ->setSetsForRound(1)
            ->setBlockType('preparacion')
            ->setName('Preparación y movilidad')
            ->setDescription('Movilidad fluida encadenada')
            ->setRestAfterBlock(60);
        $config1 = (new TrainingExerciseConfiguration())
            ->setExercise((new Exercises())->setName('Wall Slide'))
            ->setSets(1)
            ->setMaxTimeForReps(40);
        $round1->addTrainingExerciseConfiguration($config1);

        $round2 = (new TrainingRound())
            ->setRound(2)
            ->setSetsForRound(1)
            ->setBlockType('principal')
            ->setName('Fuerza — Empuje')
            ->setDescription('Trabajo de empuje del nivel');
        $config2 = (new TrainingExerciseConfiguration())
            ->setExercise((new Exercises())->setName('Flexiones'))
            ->setSets(1)
            ->setReps(8);
        $round2->addTrainingExerciseConfiguration($config2);

        $training->addTrainingRound($round1);
        $training->addTrainingRound($round2);

        $log = new UserTrainingLog();
        $this->expander->expand($training, $log);

        return $log;
    }

    private function createBenchmark(): Benchmark
    {
        $benchmark = (new Benchmark())
            ->setName('Fran')
            ->setType('wod')
            ->setFormat(BenchmarkFormat::FOR_TIME)
            ->setTimerConfig(['mode' => 'countdown'])
            ->setTimeCapSeconds(600)
            ->setRestBetweenRoundsSeconds(60)
            ->setRxWeightMale('61 kg')
            ->setRxWeightFemale('43 kg')
            ->setScalingOptions('Bajar carga si es necesario')
            ->setPartitioningAllowed(false);

        $benchmark->addExercise(
            (new BenchmarkExercise())
                ->setExercise((new Exercises())->setName('Thruster'))
                ->setPosition(0)
                ->setReps(21)
                ->setRepsPattern('21-15-9')
                ->setRxWeightMale('61 kg')
                ->setRxWeightFemale('43 kg'),
        );
        $benchmark->addExercise(
            (new BenchmarkExercise())
                ->setExercise((new Exercises())->setName('Pull Up'))
                ->setPosition(1)
                ->setReps(21)
                ->setRepsPattern('21-15-9'),
        );

        return $benchmark;
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

    private function createConfig(string $exerciseName, ?int $reps = null, ?int $maxTime = null, int $sets = 1, ?int $restSets = 60, ?int $restEx = 30): TrainingExerciseConfiguration
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
