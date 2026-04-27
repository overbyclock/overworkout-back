<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingRound;
use App\Enum\Discipline;
use App\Service\TrainingTimeCalculator;
use PHPUnit\Framework\TestCase;

class TrainingTimeCalculatorTest extends TestCase
{
    private TrainingTimeCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new TrainingTimeCalculator();
    }

    public function testEmptyTrainingReturnsZero(): void
    {
        $training = new Training();
        $training->setDiscipline(Discipline::CALISTHENICS);

        $result = $this->calculator->calculate($training);

        self::assertSame(['min' => 0, 'max' => 0], $result);
    }

    public function testCircuitWithThreeRoundsAndFourExercises(): void
    {
        $training = $this->createCircuitTraining(3, 120);

        // Ejercicios: 10 reps, 8 reps, 8 reps, 30s plank
        $training->getTrainingRounds()->first()->addTrainingExerciseConfiguration(
            $this->createConfig(reps: 10)
        );
        $training->getTrainingRounds()->first()->addTrainingExerciseConfiguration(
            $this->createConfig(reps: 8)
        );
        $training->getTrainingRounds()->first()->addTrainingExerciseConfiguration(
            $this->createConfig(reps: 8)
        );
        $training->getTrainingRounds()->first()->addTrainingExerciseConfiguration(
            $this->createConfig(maxTime: 30)
        );

        $result = $this->calculator->calculate($training);

        // Por ronda:
        //   Ej1: 10 reps * [3|5] = 30-50s
        //   Ej2: 8 reps * [3|5] = 24-40s
        //   Ej3: 8 reps * [3|5] = 24-40s
        //   Ej4: 30s = 30s
        //   Descansos entre ejercicios (3 x 30s) = 90s
        //   Ronda min: 30+24+24+30+90 = 198s
        //   Ronda max: 50+40+40+30+90 = 250s
        // 3 rondas + 2 descansos entre rondas (120s)
        //   Min: 3*198 + 2*120 = 594 + 240 = 834s
        //   Max: 3*250 + 2*120 = 750 + 240 = 990s
        self::assertSame(834, $result['min']);
        self::assertSame(990, $result['max']);
    }

    public function testTraditionalTrainingWithSets(): void
    {
        $training = new Training();
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setIsCircuit(false);

        $round = new TrainingRound();
        $round->setSetsForRound(1);
        $round->setRestBetweenRounds(120);

        // Ejercicio 1: 12 reps, 3 sets, 60s descanso
        $round->addTrainingExerciseConfiguration(
            $this->createConfig(reps: 12, sets: 3, restBetweenSets: 60, restBetweenExercises: 30)
        );
        // Ejercicio 2: 10 reps, 3 sets, 60s descanso
        $round->addTrainingExerciseConfiguration(
            $this->createConfig(reps: 10, sets: 3, restBetweenSets: 60, restBetweenExercises: 30)
        );

        $training->addTrainingRound($round);

        $result = $this->calculator->calculate($training);

        // Ej1: 3 sets * [36|60]s + 2 descansos * 60s = 108-180 + 120 = 228-300s
        // Ej2: 3 sets * [30|50]s + 2 descansos * 60s = 90-150 + 120 = 210-270s
        // Descanso entre ejercicios: 30s
        // Total min: 228 + 210 + 30 = 468s
        // Total max: 300 + 270 + 30 = 600s
        self::assertSame(468, $result['min']);
        self::assertSame(600, $result['max']);
    }

    public function exerciseWithFallbackTime(): void
    {
        $training = new Training();
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setIsCircuit(true);

        $round = new TrainingRound();
        $round->setSetsForRound(1);
        $round->setRestBetweenRounds(0);
        $round->addTrainingExerciseConfiguration(
            $this->createConfig(reps: null, maxTime: null)
        );
        $training->addTrainingRound($round);

        $result = $this->calculator->calculate($training);

        self::assertSame(30, $result['min']);
        self::assertSame(45, $result['max']);
    }

    public function testTimeBasedExerciseReturnsExactSeconds(): void
    {
        $training = new Training();
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setIsCircuit(true);

        $round = new TrainingRound();
        $round->setSetsForRound(2);
        $round->setRestBetweenRounds(60);
        $round->addTrainingExerciseConfiguration(
            $this->createConfig(maxTime: 45)
        );
        $training->addTrainingRound($round);

        $result = $this->calculator->calculate($training);

        // 2 rondas * 45s + 1 descanso * 60s = 90 + 60 = 150s
        self::assertSame(150, $result['min']);
        self::assertSame(150, $result['max']);
    }

    public function testMultipleTrainingRoundsAreSummed(): void
    {
        $training = new Training();
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setIsCircuit(false);

        $round1 = new TrainingRound();
        $round1->setSetsForRound(1);
        $round1->setRestBetweenRounds(90);
        $round1->addTrainingExerciseConfiguration(
            $this->createConfig(reps: 10, sets: 1, restBetweenExercises: 1)
        );
        $training->addTrainingRound($round1);

        $round2 = new TrainingRound();
        $round2->setSetsForRound(1);
        $round2->setRestBetweenRounds(90);
        $round2->addTrainingExerciseConfiguration(
            $this->createConfig(reps: 5, sets: 1, restBetweenExercises: 1)
        );
        $training->addTrainingRound($round2);

        $result = $this->calculator->calculate($training);

        // Round1: 10 reps * 3-5 = 30-50s
        // Round2: 5 reps * 3-5 = 15-25s
        // Descanso entre rounds: 90s (del primer round)
        // Total min: 30 + 15 + 90 = 135s
        // Total max: 50 + 25 + 90 = 165s
        self::assertSame(135, $result['min']);
        self::assertSame(165, $result['max']);
    }

    private function createCircuitTraining(int $rounds, int $restBetweenRounds): Training
    {
        $training = new Training();
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setIsCircuit(true);

        $round = new TrainingRound();
        $round->setSetsForRound($rounds);
        $round->setRestBetweenRounds($restBetweenRounds);

        $training->addTrainingRound($round);

        return $training;
    }

    private function createConfig(
        ?int $reps = null,
        ?int $maxTime = null,
        int $sets = 1,
        int $restBetweenSets = 30,
        int $restBetweenExercises = 30,
    ): TrainingExerciseConfiguration {
        $config = new TrainingExerciseConfiguration();
        $config->setExercise(new Exercises());
        $config->setReps($reps);
        $config->setMaxTimeForReps($maxTime);
        $config->setSets($sets);
        $config->setRestBetweenSets($restBetweenSets);
        $config->setRestBetweenExercises($restBetweenExercises);

        return $config;
    }
}
