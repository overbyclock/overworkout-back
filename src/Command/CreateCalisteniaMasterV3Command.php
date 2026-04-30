<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Blueprint\CalisteniaMasterBlueprintV3;
use App\Command\Blueprint\CalisteniaMasterContentV3;
use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingRound;
use App\Entity\TrainingWeekInfo;
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
    name: 'app:create-calistenia-master-v3',
    description: 'Create or recreate Calistenia Master v3.0 program (12 levels, unlimited cycles, 192 trainings)',
)]
class CreateCalisteniaMasterV3Command extends Command
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
            ->addOption('delete-existing', 'd', InputOption::VALUE_NONE, 'Delete existing Calistenia Master v3 trainings before creating')
            ->addOption('levels', 'l', InputOption::VALUE_OPTIONAL, 'Comma-separated levels to create (e.g. 1,2,3). Default: all 1-12', '1,2,3,4,5,6,7,8,9,10,11,12')
            ->addOption('slug', 's', InputOption::VALUE_OPTIONAL, 'Program slug', 'calisthenia-master-v3');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('🎯 Calistenia Master v3.0 - Program Generator');

        $levels = array_map('intval', explode(',', $input->getOption('levels')));
        $slug = $input->getOption('slug');

        if ($input->getOption('delete-existing')) {
            $this->deleteExisting($slug);
        }

        $program = $this->getOrCreateProgram($slug);

        foreach ($levels as $levelNum) {
            $this->createLevel($program, $levelNum);
        }

        $this->em->flush();

        $this->io->success('Calistenia Master v3.0 created successfully! Levels: '.implode(', ', $levels));

        return Command::SUCCESS;
    }

    private function deleteExisting(string $slug): void
    {
        $this->io->section('Deleting existing Calistenia Master v3 data...');

        $program = $this->em->getRepository(TrainingProgram::class)->findOneBy(['slug' => $slug]);
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
                ++$countTrainings;
            }
            $this->em->remove($level);
            ++$countLevels;
        }

        $this->em->remove($program);
        $this->em->flush();
        $this->io->text("Deleted {$countLevels} levels and {$countTrainings} trainings");
    }

    private function getOrCreateProgram(string $slug): TrainingProgram
    {
        $program = $this->em->getRepository(TrainingProgram::class)->findOneBy(['slug' => $slug]);

        if ($program) {
            $this->io->note("Program '{$slug}' already exists, reusing");

            return $program;
        }

        $program = new TrainingProgram();
        $program->setName('Calistenia Master v3.0');
        $program->setSlug($slug);
        $program->setDescription('Programa de 12 niveles para dominar la calistenia v3.0. Ciclos ilimitados con 4 fases (Base→Progresion→Intensificacion→Recuperacion), sesiones A/B/C/D de fuerza, circuito y skills.');
        $program->setDiscipline('calisthenics');
        $program->setTotalLevels(12);
        $program->setEstimatedDurationWeeks(48);
        $program->setDifficulty('beginner');
        $program->setIsActive(true);

        $this->em->persist($program);
        $this->em->flush();

        $this->io->success("Program '{$slug}' created");

        return $program;
    }

    private function createLevel(TrainingProgram $program, int $levelNum): void
    {
        $this->io->section("Creating Level {$levelNum}");

        $meta = CalisteniaMasterBlueprintV3::getLevelMeta($levelNum);

        $level = $this->em->getRepository(TrainingLevel::class)->findOneBy([
            'program' => $program,
            'levelNumber' => $levelNum,
        ]);

        if ($level) {
            foreach ($level->getTrainings() as $training) {
                $this->em->remove($training);
            }
            $this->em->flush();
            $this->io->text("  Cleared existing trainings for Level {$levelNum}");
        } else {
            $level = new TrainingLevel();
            $level->setProgram($program);
            $level->setLevelNumber($levelNum);
            $level->setEstimatedDurationWeeks(4);
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
        $level->setSkillFocus($meta['skillFocus'] ?? null);
        $level->setProgramVersion('v3');
        $level->setCyclesCompleted(0);

        // Aplicar contenido educativo (tips, weekInfos)
        $this->applyLevelContent($level, $levelNum);

        $sessions = ['session_a', 'session_b', 'session_c', 'session_d'];
        $weeks = [0 => 'base', 1 => 'progression', 2 => 'intensification', 3 => 'deload'];

        foreach ($weeks as $weekNum => $phase) {
            foreach ($sessions as $sessionKey) {
                $data = CalisteniaMasterBlueprintV3::getSessionData($levelNum, $sessionKey, $phase);
                $this->createTraining($level, $data, $weekNum, $sessionKey, $levelNum);
            }
        }

        $this->io->text("  Created Level {$levelNum} with 16 trainings");
    }

    private function createTraining(TrainingLevel $level, array $data, int $weekNum, string $sessionKey, int $levelNum): void
    {
        $training = new Training();
        $training->setName($data['name'].' - Fase '.$weekNum);
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setIsBenchmark(false);
        // strength sessions are NOT circuits; circuit sessions ARE circuits
        $training->setIsCircuit('circuit' === $data['sessionType']);
        $training->setSessionType($data['sessionType']);
        $training->setTrainingLevel($level);
        $training->setWeekNumber($weekNum);
        $training->setDayKey($sessionKey);

        $this->em->persist($training);

        foreach ($data['blocks'] as $blockData) {
            $this->createTrainingRound($training, $blockData, $levelNum);
        }

        // Auto-calculate duration
        $duration = $this->calculator->calculate($training);
        $training->setEstimatedDurationMin($duration['min']);
        $training->setEstimatedDurationMax($duration['max']);
    }

    private function createTrainingRound(Training $training, array $blockData, int $levelNum): void
    {
        $round = new TrainingRound();
        $round->setTraining($training);
        $training->addTrainingRound($round);
        $round->setSetsForRound($blockData['rounds']);
        $round->setRestBetweenRounds($blockData['restBetweenRounds']);

        if (isset($blockData['restAfterBlock'])) {
            $round->setRestAfterBlock($blockData['restAfterBlock']);
        }

        $this->em->persist($round);

        $restBetweenSets = $blockData['restBetweenSets'] ?? $blockData['restBetweenExercises'];
        foreach ($blockData['exercises'] as $exerciseData) {
            $this->createExerciseConfig($round, $exerciseData, $blockData['restBetweenExercises'], $restBetweenSets, $levelNum);
        }
    }

    private function createExerciseConfig(TrainingRound $round, array $exData, int $restBetweenExercises, int $restBetweenSets, int $levelNum): void
    {
        $exercise = $this->em->getRepository(Exercises::class)->findOneBy(['name' => $exData['name']]);

        if (!$exercise) {
            $this->io->warning("  Exercise '{$exData['name']}' not found, skipping...");

            return;
        }

        $config = new TrainingExerciseConfiguration();
        $config->setTrainingRound($round);
        $round->addTrainingExerciseConfiguration($config);
        $config->setExercise($exercise);
        $config->setSets($exData['sets'] ?? 1);

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

        $config->setRestBetweenSets($restBetweenSets > 0 ? $restBetweenSets : 1);
        $config->setRestBetweenExercises($restBetweenExercises);
        $config->setWeight(null);

        // Aplicar nota tecnica del contenido educativo
        $note = CalisteniaMasterContentV3::getExerciseNote($levelNum, $exData['name']);
        if ($note) {
            $config->setNotes($note);
        }

        $this->em->persist($config);
    }

    private function applyLevelContent(TrainingLevel $level, int $levelNum): void
    {
        $content = CalisteniaMasterContentV3::getLevelContent($levelNum);

        // Tips
        $level->setTips($content['tips']);

        // Test requirements (exit criteria)
        if (isset($content['testWeek']['tests']['requirements'])) {
            $level->setTestRequirements($content['testWeek']['tests']['requirements']);
        }

        // Limpiar weekInfos existentes
        foreach ($level->getWeekInfos() as $existing) {
            $level->removeWeekInfo($existing);
            $this->em->remove($existing);
        }

        // Crear nuevos weekInfos
        foreach ($content['trainingWeeks'] as $weekInfoData) {
            $weekInfo = new TrainingWeekInfo();
            $weekInfo->setTrainingLevel($level);
            $weekInfo->setWeekNumber($weekInfoData['week']);
            $weekInfo->setName($weekInfoData['name']);
            $weekInfo->setFocus($weekInfoData['focus']);
            $weekInfo->setNote($weekInfoData['note']);
            $weekInfo->setIntensity($weekInfoData['intensity']);
            $this->em->persist($weekInfo);
        }
    }
}
