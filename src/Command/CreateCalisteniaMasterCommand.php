<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Blueprint\CalisteniaMasterBlueprint;
use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingRound;
use App\Enum\Discipline;
use App\Service\TrainingTimeCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-calistenia-master',
    description: 'Create or recreate the complete Calistenia Master program (12 levels, 192 trainings)',
)]
class CreateCalisteniaMasterCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private EntityManagerInterface $em,
        private TrainingTimeCalculator $calculator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('delete-existing', 'd', InputOption::VALUE_NONE, 'Delete existing Calistenia Master trainings before creating')
            ->addOption('levels', 'l', InputOption::VALUE_OPTIONAL, 'Comma-separated levels to create (e.g. 1,2,3). Default: all 1-12', '1,2,3,4,5,6,7,8,9,10,11,12');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('🎯 Calistenia Master - Program Generator');

        $levels = array_map('intval', explode(',', $input->getOption('levels')));

        if ($input->getOption('delete-existing')) {
            $this->deleteExisting();
        }

        $program = $this->getOrCreateProgram();

        foreach ($levels as $levelNum) {
            $this->createLevel($program, $levelNum);
        }

        $this->em->flush();

        $this->io->success("Calistenia Master created successfully! Levels: " . implode(', ', $levels));

        return Command::SUCCESS;
    }

    private function deleteExisting(): void
    {
        $this->io->section('Deleting existing Calistenia Master data...');

        $program = $this->em->getRepository(TrainingProgram::class)->findOneBy(['slug' => 'calisthenia-master']);
        if (!$program) {
            $this->io->note('No existing program found');
            return;
        }

        $levels = $this->em->getRepository(TrainingLevel::class)->findBy(['program' => $program]);
        $countTrainings = 0;
        $countLevels = 0;

        foreach ($levels as $level) {
            foreach ($level->getTrainings() as $training) {
                $this->em->remove($training);
                $countTrainings++;
            }
            $this->em->remove($level);
            $countLevels++;
        }

        $this->em->remove($program);
        $this->em->flush();
        $this->io->text("Deleted {$countLevels} levels and {$countTrainings} trainings");
    }

    private function getOrCreateProgram(): TrainingProgram
    {
        $program = $this->em->getRepository(TrainingProgram::class)->findOneBy(['slug' => 'calisthenia-master']);

        if ($program) {
            $this->io->note('Program "Calistenia Master" already exists, reusing');
            return $program;
        }

        $program = new TrainingProgram();
        $program->setName('Calistenia Master');
        $program->setSlug('calisthenia-master');
        $program->setDescription('Programa de 12 niveles para dominar la calistenia. Progresión estructurada desde principiante hasta élite.');
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

    private function createLevel(TrainingProgram $program, int $levelNum): void
    {
        $this->io->section("Creating Level {$levelNum}");

        $meta = CalisteniaMasterBlueprint::getLevelMeta($levelNum);

        $level = $this->em->getRepository(TrainingLevel::class)->findOneBy([
            'program' => $program,
            'levelNumber' => $levelNum,
        ]);

        if ($level) {
            // Delete existing trainings for this level to recreate
            foreach ($level->getTrainings() as $training) {
                $this->em->remove($training);
            }
            $this->em->flush();
            $this->io->text("  Cleared existing trainings for Level {$levelNum}");
        } else {
            $level = new TrainingLevel();
            $level->setProgram($program);
            $level->setLevelNumber($levelNum);
            $level->setEstimatedDurationWeeks(5);
            $level->setIsLockedByDefault($levelNum > 1);
            $this->em->persist($level);
        }

        $level->setName($meta['name']);
        $level->setTitle($meta['title']);
        $level->setDescription($meta['description']);
        $level->setObjective($meta['objective']);
        $level->setDifficultyRating($meta['difficultyRating']);
        $level->setColor($meta['color']);
        $level->setIcon('fitness_center');
        $level->setRequirementsSummary($meta['requirementsSummary']);

        $days = ['day1_push', 'day2_pull', 'day3_legs', 'day4_core'];
        $weeks = [0 => 'base', 1 => 'base', 2 => 'progression', 3 => 'intensification'];

        foreach ($weeks as $weekNum => $phase) {
            foreach ($days as $dayKey) {
                $data = CalisteniaMasterBlueprint::getDayData($levelNum, $dayKey, $phase);
                $this->createTraining($level, $data, $weekNum, $dayKey);
            }
        }

        $this->io->text("  Created Level {$levelNum} with 16 trainings");
    }

    private function createTraining(TrainingLevel $level, array $data, int $weekNum, string $dayKey): void
    {
        $training = new Training();
        $training->setName($data['name'] . ' - Semana ' . $weekNum);
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setIsBenchmark(false);
        $training->setIsCircuit(true);
        $training->setTrainingLevel($level);
        $training->setWeekNumber($weekNum);
        $training->setDayKey($dayKey);

        $this->em->persist($training);

        foreach ($data['blocks'] as $blockData) {
            $this->createTrainingRound($training, $blockData);
        }

        // Auto-calculate duration
        $duration = $this->calculator->calculate($training);
        $training->setEstimatedDurationMin($duration['min']);
        $training->setEstimatedDurationMax($duration['max']);
    }

    private function createTrainingRound(Training $training, array $blockData): void
    {
        $round = new TrainingRound();
        $round->setTraining($training);
        $round->setSetsForRound($blockData['rounds']);
        $round->setRestBetweenRounds($blockData['restBetweenRounds']);

        $this->em->persist($round);

        foreach ($blockData['exercises'] as $exerciseData) {
            $this->createExerciseConfig($round, $exerciseData, $blockData['restBetweenExercises']);
        }
    }

    private function createExerciseConfig(TrainingRound $round, array $exData, int $restBetweenExercises): void
    {
        $exercise = $this->em->getRepository(Exercises::class)->findOneBy(['name' => $exData['name']]);

        if (!$exercise) {
            $this->io->warning("  Exercise '{$exData['name']}' not found, skipping...");
            return;
        }

        $config = new TrainingExerciseConfiguration();
        $config->setTrainingRound($round);
        $config->setExercise($exercise);
        $config->setSets(1);

        $reps = $exData['reps'];
        if (str_contains($reps, 's')) {
            $config->setReps(null);
            $timeMatch = preg_match('/(\d+)(?:-(\d+))?s/', $reps, $matches);
            if ($timeMatch) {
                $maxTime = $matches[2] ?? $matches[1];
                $config->setMaxTimeForReps((int) $maxTime);
            }
        } else {
            $repMatch = preg_match('/(\d+)(?:-(\d+))?/', $reps, $matches);
            if ($repMatch) {
                $maxReps = $matches[2] ?? $matches[1];
                $config->setReps((int) $maxReps);
            }
            $config->setMaxTimeForReps(null);
        }

        $config->setRestBetweenSets($restBetweenExercises > 0 ? $restBetweenExercises : 1);
        $config->setRestBetweenExercises($restBetweenExercises);
        $config->setWeight(null);

        $this->em->persist($config);
    }
}
