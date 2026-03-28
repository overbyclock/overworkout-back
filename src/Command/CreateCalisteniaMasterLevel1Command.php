<?php

namespace App\Command;

use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingRound;
use App\Enum\Discipline;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-calistenia-master-level1',
    description: 'Create Calistenia Master Level 1 with all training sessions',
)]
class CreateCalisteniaMasterLevel1Command extends Command
{
    private EntityManagerInterface $em;
    private SymfonyStyle $io;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('Creating Calistenia Master - Level 1');

        // 1. Create or get the Program
        $program = $this->createProgram();
        
        // 2. Create Level 1
        $level = $this->createLevel1($program);
        
        // 3. Create all training sessions
        $this->createWeek01Trainings($level);
        $this->createWeek2Trainings($level);
        $this->createWeek3Trainings($level);
        
        $this->em->flush();
        
        $this->io->success('Calistenia Master Level 1 created successfully!');
        
        return Command::SUCCESS;
    }

    private function createProgram(): TrainingProgram
    {
        $program = $this->em->getRepository(TrainingProgram::class)->findOneBy(['slug' => 'calistenia-master']);
        
        if ($program) {
            $this->io->note('Program "Calistenia Master" already exists');
            return $program;
        }
        
        $program = new TrainingProgram();
        $program->setName('Calistenia Master');
        $program->setSlug('calistenia-master');
        $program->setDescription('Programa de 12 niveles para dominar la calistenia. Progresión estructurada desde principiante hasta avanzado.');
        $program->setDiscipline('calisthenics');
        $program->setTotalLevels(12);
        $program->setEstimatedDurationWeeks(52);
        $program->setDifficulty('beginner');
        $program->setIsActive(true);
        
        $this->em->persist($program);
        $this->em->flush();
        
        $this->io->success('Program "Calistenia Master" created');
        
        return $program;
    }

    private function createLevel1(TrainingProgram $program): TrainingLevel
    {
        $level = $this->em->getRepository(TrainingLevel::class)->findOneBy([
            'program' => $program,
            'levelNumber' => 1
        ]);
        
        if ($level) {
            $this->io->note('Level 1 already exists');
            return $level;
        }
        
        $level = new TrainingLevel();
        $level->setProgram($program);
        $level->setLevelNumber(1);
        $level->setName('Nivel 1: Fundamentos');
        $level->setTitle('Fundamentos');
        $level->setDescription('Construye la base para superar los tests: Push-ups, Australian Pull-ups, Squats, Plank y Hollow Body');
        $level->setObjective('Desarrollar fuerza básica y técnica correcta en los movimientos fundamentales de calistenia');
        $level->setEstimatedDurationWeeks(5);
        $level->setDifficultyRating(1);
        $level->setColor('#4ade80'); // Verde para principiante
        $level->setIcon('fitness_center');
        $level->setRequirementsSummary('Superar 4 de 5 tests: Push-ups (10), Australian Pull-ups (12), Air Squats (20), Plank (45s), Hollow Body (20s)');
        $level->setIsLockedByDefault(false); // Primer nivel desbloqueado
        
        $this->em->persist($level);
        $this->em->flush();
        
        $this->io->success('Level 1 created');
        
        return $level;
    }

    private function createWeek01Trainings(TrainingLevel $level): void
    {
        $this->io->section('Creating Week 0 & 1 Trainings (Base)');
        
        $sessions = [
            'day1_push' => [
                'name' => 'Día 1: Push Base',
                'goal' => 'Preparar Push-up test',
                'rounds' => 3,
                'restBetweenRounds' => 120,
                'restBetweenExercises' => 30,
                'exercises' => [
                    ['id' => 10, 'reps' => '10-15', 'sets' => 1, 'rest' => 30],
                    ['id' => 11, 'reps' => '8-10', 'sets' => 1, 'rest' => 30],
                    ['id' => 232, 'reps' => '8-10', 'sets' => 1, 'rest' => 30],
                    ['id' => 286, 'reps' => '20-30s', 'sets' => 1, 'rest' => 0],
                ]
            ],
            'day2_pull' => [
                'name' => 'Día 2: Pull Base',
                'goal' => 'Preparar Australian Pull-up test',
                'rounds' => 3,
                'restBetweenRounds' => 120,
                'restBetweenExercises' => 30,
                'exercises' => [
                    ['id' => 42, 'reps' => '8-10', 'sets' => 1, 'rest' => 30],
                    ['id' => 273, 'reps' => '15-20s', 'sets' => 1, 'rest' => 30],
                    ['id' => 20, 'reps' => '6-8', 'sets' => 1, 'rest' => 30],
                    ['id' => 298, 'reps' => '15-20s', 'sets' => 1, 'rest' => 0],
                ]
            ],
            'day3_legs' => [
                'name' => 'Día 3: Legs Base',
                'goal' => 'Preparar Air Squat test',
                'rounds' => 3,
                'restBetweenRounds' => 120,
                'restBetweenExercises' => 30,
                'exercises' => [
                    ['id' => 148, 'reps' => '10-12', 'sets' => 1, 'rest' => 30],
                    ['id' => 147, 'reps' => '8-10', 'sets' => 1, 'rest' => 30],
                    ['id' => 107, 'reps' => '12-15', 'sets' => 1, 'rest' => 30],
                    ['id' => 188, 'reps' => '12-15', 'sets' => 1, 'rest' => 0],
                ]
            ],
            'day4_core' => [
                'name' => 'Día 4: Core Base',
                'goal' => 'Preparar Plank y Hollow Body tests',
                'rounds' => 3,
                'restBetweenRounds' => 90,
                'restBetweenExercises' => 20,
                'exercises' => [
                    ['id' => 294, 'reps' => '8-10', 'sets' => 1, 'rest' => 20],
                    ['id' => 288, 'reps' => '15-20s', 'sets' => 1, 'rest' => 20],
                    ['id' => 290, 'reps' => '6-8', 'sets' => 1, 'rest' => 20],
                    ['id' => 298, 'reps' => '15-20s', 'sets' => 1, 'rest' => 0],
                ]
            ],
        ];
        
        // Create for weeks 0 and 1
        foreach ([0, 1] as $weekNum) {
            foreach ($sessions as $dayKey => $sessionData) {
                $training = $this->createTraining($level, $sessionData, $weekNum, $dayKey);
                $this->createTrainingRound($training, $sessionData);
            }
        }
        
        $this->io->success('Week 0 & 1 trainings created (8 sessions)');
    }

    private function createWeek2Trainings(TrainingLevel $level): void
    {
        $this->io->section('Creating Week 2 Trainings (Progression)');
        
        $sessions = [
            'day1_push' => [
                'name' => 'Día 1: Push Progresión',
                'goal' => 'Acercarse al push-up completo',
                'rounds' => 3,
                'restBetweenRounds' => 120,
                'restBetweenExercises' => 30,
                'exercises' => [
                    ['id' => 13, 'reps' => '10-12', 'sets' => 1, 'rest' => 30],
                    ['id' => 11, 'reps' => '10-12', 'sets' => 1, 'rest' => 30],
                    ['id' => 14, 'reps' => '3-5', 'sets' => 1, 'rest' => 30],
                    ['id' => 286, 'reps' => '30-40s', 'sets' => 1, 'rest' => 0],
                ]
            ],
            'day2_pull' => [
                'name' => 'Día 2: Pull Progresión',
                'goal' => 'Aumentar reps en Australian Pull-up',
                'rounds' => 3,
                'restBetweenRounds' => 120,
                'restBetweenExercises' => 30,
                'exercises' => [
                    ['id' => 20, 'reps' => '10-12', 'sets' => 1, 'rest' => 30],
                    ['id' => 274, 'reps' => '20-30s', 'sets' => 1, 'rest' => 30],
                    ['id' => 43, 'reps' => '8-10', 'sets' => 1, 'rest' => 30],
                    ['id' => 71, 'reps' => '8-10', 'sets' => 1, 'rest' => 0],
                ]
            ],
            'day3_legs' => [
                'name' => 'Día 3: Legs Progresión',
                'goal' => 'Llegar a 20 squats seguidos',
                'rounds' => 3,
                'restBetweenRounds' => 120,
                'restBetweenExercises' => 30,
                'exercises' => [
                    ['id' => 146, 'reps' => '15-18', 'sets' => 1, 'rest' => 30],
                    ['id' => 149, 'reps' => '8-10', 'sets' => 1, 'rest' => 30],
                    ['id' => 181, 'reps' => '12-15', 'sets' => 1, 'rest' => 30],
                    ['id' => 108, 'reps' => '8-10', 'sets' => 1, 'rest' => 0],
                ]
            ],
            'day4_core' => [
                'name' => 'Día 4: Core Progresión',
                'goal' => 'Construir resistencia para plank 45s',
                'rounds' => 3,
                'restBetweenRounds' => 120,
                'restBetweenExercises' => 25,
                'exercises' => [
                    ['id' => 286, 'reps' => '35-45s', 'sets' => 1, 'rest' => 25],
                    ['id' => 287, 'reps' => '20-25s', 'sets' => 1, 'rest' => 25],
                    ['id' => 294, 'reps' => '10-12', 'sets' => 1, 'rest' => 25],
                    ['id' => 296, 'reps' => '15-20s', 'sets' => 1, 'rest' => 0],
                ]
            ],
        ];
        
        foreach ($sessions as $dayKey => $sessionData) {
            $training = $this->createTraining($level, $sessionData, 2, $dayKey);
            $this->createTrainingRound($training, $sessionData);
        }
        
        $this->io->success('Week 2 trainings created (4 sessions)');
    }

    private function createWeek3Trainings(TrainingLevel $level): void
    {
        $this->io->section('Creating Week 3 Trainings (Intensification)');
        
        $sessions = [
            'day1_push' => [
                'name' => 'Día 1: Push Intensificación',
                'goal' => 'Simular test de Push-ups',
                'rounds' => 4,
                'restBetweenRounds' => 180,
                'restBetweenExercises' => 45,
                'exercises' => [
                    ['id' => 11, 'reps' => '12-15', 'sets' => 1, 'rest' => 45],
                    ['id' => 13, 'reps' => '12-15', 'sets' => 1, 'rest' => 45],
                    ['id' => 232, 'reps' => '12-15', 'sets' => 1, 'rest' => 45],
                    ['id' => 286, 'reps' => '45-60s', 'sets' => 1, 'rest' => 0],
                ]
            ],
            'day2_pull' => [
                'name' => 'Día 2: Pull Intensificación',
                'goal' => 'Simular test de Australian Pull-ups',
                'rounds' => 4,
                'restBetweenRounds' => 180,
                'restBetweenExercises' => 45,
                'exercises' => [
                    ['id' => 20, 'reps' => '12-15', 'sets' => 1, 'rest' => 45],
                    ['id' => 42, 'reps' => '10-12', 'sets' => 1, 'rest' => 45],
                    ['id' => 274, 'reps' => '30-40s', 'sets' => 1, 'rest' => 45],
                    ['id' => 273, 'reps' => '30-40s', 'sets' => 1, 'rest' => 0],
                ]
            ],
            'day3_legs' => [
                'name' => 'Día 3: Legs Intensificación',
                'goal' => 'Simular test de Air Squats (20 reps)',
                'rounds' => 4,
                'restBetweenRounds' => 180,
                'restBetweenExercises' => 45,
                'exercises' => [
                    ['id' => 146, 'reps' => '18-20', 'sets' => 1, 'rest' => 45],
                    ['id' => 147, 'reps' => '15-18', 'sets' => 1, 'rest' => 45],
                    ['id' => 188, 'reps' => '15-20', 'sets' => 1, 'rest' => 45],
                    ['id' => 107, 'reps' => '15-18', 'sets' => 1, 'rest' => 0],
                ]
            ],
            'day4_core' => [
                'name' => 'Día 4: Core Intensificación + Tests',
                'goal' => 'Simular tests de Plank y Hollow Body',
                'rounds' => 3,
                'restBetweenRounds' => 180,
                'restBetweenExercises' => 30,
                'exercises' => [
                    ['id' => 286, 'reps' => '45-60s', 'sets' => 1, 'rest' => 30],
                    ['id' => 296, 'reps' => '20-30s', 'sets' => 1, 'rest' => 30],
                    ['id' => 294, 'reps' => '12-15', 'sets' => 1, 'rest' => 30],
                    ['id' => 287, 'reps' => '25-30s', 'sets' => 1, 'rest' => 0],
                ]
            ],
        ];
        
        foreach ($sessions as $dayKey => $sessionData) {
            $training = $this->createTraining($level, $sessionData, 3, $dayKey);
            $this->createTrainingRound($training, $sessionData);
        }
        
        $this->io->success('Week 3 trainings created (4 sessions)');
    }

    private function createTraining(TrainingLevel $level, array $sessionData, int $weekNum, string $dayKey): Training
    {
        $training = new Training();
        $training->setName($sessionData['name'] . ' - Semana ' . $weekNum);
        $training->setDiscipline(Discipline::CALISTHENICS);
        // Target se deja como está (sin establecer)
        $training->setRounds($sessionData['rounds']);
        $training->setIsBenchmark(false);
        $training->setIsCircuit(true);
        $training->setTrainingLevel($level);
        $training->setWeekNumber($weekNum);
        $training->setDayKey($dayKey);
        
        $this->em->persist($training);
        
        $this->io->text("  Created: {$sessionData['name']} (Week {$weekNum})");
        
        return $training;
    }

    private function createTrainingRound(Training $training, array $sessionData): void
    {
        $round = new TrainingRound();
        $round->setTraining($training);
        $round->setRound($sessionData['rounds']); // Total rounds for this training
        $round->setRestBetweenRounds($sessionData['restBetweenRounds']);
        
        $this->em->persist($round);
        
        // Create exercise configurations
        foreach ($sessionData['exercises'] as $index => $exerciseData) {
            $exercise = $this->em->getRepository(Exercises::class)->find($exerciseData['id']);
            
            if (!$exercise) {
                $this->io->warning("Exercise ID {$exerciseData['id']} not found, skipping...");
                continue;
            }
            
            $config = new TrainingExerciseConfiguration();
            $config->setTrainingRound($round);
            $config->setExercise($exercise);
            $config->setSets($exerciseData['sets']);
            
            // Parse reps (could be "10-12" or "20-30s")
            $reps = $exerciseData['reps'];
            if (str_contains($reps, 's')) {
                // Time-based exercise
                $config->setReps(null);
                $timeMatch = preg_match('/(\d+)(?:-(\d+))?s/', $reps, $matches);
                if ($timeMatch) {
                    $maxTime = $matches[2] ?? $matches[1];
                    $config->setMaxTimeForReps((int)$maxTime);
                }
            } else {
                // Rep-based exercise
                $repMatch = preg_match('/(\d+)(?:-(\d+))?/', $reps, $matches);
                if ($repMatch) {
                    $maxReps = $matches[2] ?? $matches[1];
                    $config->setReps((int)$maxReps);
                }
                $config->setMaxTimeForReps(null);
            }
            
            $config->setRestBetweenSets($exerciseData['rest'] > 0 ? $exerciseData['rest'] : 1);
            $config->setRestBetweenExercises($sessionData['restBetweenExercises']);
            $config->setWeight(null);
            
            $this->em->persist($config);
        }
    }
}
