<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Blueprint\HandstandBalanceBlueprint;
use App\Command\Blueprint\HandstandBalanceContent;
use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingRound;
use App\Entity\TrainingWeekInfo;
use App\Enum\Discipline;
use App\Enum\Levels;
use App\Enum\MuscleGroup;
use App\Service\TrainingTimeCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-handstand-balance-mastery',
    description: 'Create or recreate Handstand Balance Mastery program (6 levels, 96 trainings)',
)]
class CreateHandstandBalanceMasteryCommand extends Command
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
            ->addOption('delete-existing', 'd', InputOption::VALUE_NONE, 'Delete existing Handstand Balance Mastery before creating')
            ->addOption('levels', 'l', InputOption::VALUE_OPTIONAL, 'Comma-separated levels to create (e.g. 1,2,3). Default: all 1-6', '1,2,3,4,5,6')
            ->addOption('slug', 's', InputOption::VALUE_OPTIONAL, 'Program slug', 'handstand-balance-mastery');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('🤸 Handstand Balance Mastery - Program Generator');

        $levels = array_map('intval', explode(',', $input->getOption('levels')));
        $slug = $input->getOption('slug');

        // 1. Seed missing exercises
        $this->seedMissingExercises();

        if ($input->getOption('delete-existing')) {
            $this->deleteExisting($slug);
        }

        $program = $this->getOrCreateProgram($slug);

        foreach ($levels as $levelNum) {
            $this->createLevel($program, $levelNum);
            $this->em->flush();
        }

        $this->io->success('Handstand Balance Mastery created successfully! Levels: '.implode(', ', $levels));

        return Command::SUCCESS;
    }

    private function seedMissingExercises(): void
    {
        $this->io->section('Checking required exercises...');

        $required = [
            [
                'name' => 'Handstand Bail Out',
                'primary' => MuscleGroup::SHOULDERS,
                'secondary' => null,
                'level' => Levels::BEGINNER,
                'difficulty' => 2,
                'description' => 'Caída controlada desde handstand. Gira lateralmente desde la posición invertida para aterrizar de pie de forma segura. Skill fundamental para perder el miedo al handstand libre.',
                'disciplines' => ['calisthenics'],
            ],
            [
                'name' => 'Kick-up to Handstand',
                'primary' => MuscleGroup::SHOULDERS,
                'secondary' => MuscleGroup::CORE,
                'level' => Levels::INTERMEDIATE,
                'difficulty' => 4,
                'description' => 'Entrada al handstand pateando desde el suelo. Una pierna impulsa, la otra sigue. Cuerpo se alinea en vertical sobre las manos. Base del handstand libre.',
                'disciplines' => ['calisthenics'],
            ],
            [
                'name' => 'Wall Scissors',
                'primary' => MuscleGroup::SHOULDERS,
                'secondary' => MuscleGroup::CORE,
                'level' => Levels::INTERMEDIATE,
                'difficulty' => 3,
                'description' => 'Desde wall handstand (pecho a pared), alternar pies despegados sin tocar la pared. Desarrolla equilibrio y control de peso sobre las manos con asistencia mínima del muro.',
                'disciplines' => ['calisthenics'],
            ],
            [
                'name' => 'Handstand Shoulder Taps',
                'primary' => MuscleGroup::SHOULDERS,
                'secondary' => MuscleGroup::CORE,
                'level' => Levels::INTERMEDIATE,
                'difficulty' => 3,
                'description' => 'Desde wall handstand (espalda a pared), tocar alternativamente cada hombro con la mano contraria manteniendo el equilibrio. Desarrolla estabilidad lateral y control de peso.',
                'disciplines' => ['calisthenics'],
            ],
            [
                'name' => 'Handstand Tuck Hold',
                'primary' => MuscleGroup::SHOULDERS,
                'secondary' => MuscleGroup::CORE,
                'level' => Levels::EXPERT,
                'difficulty' => 7,
                'description' => 'Handstand libre manteniendo las rodillas al pecho (posición tuck). Requiere equilibrio avanzado y compresión de core. Transición fundamental hacia shapes más complejas.',
                'disciplines' => ['calisthenics'],
            ],
            [
                'name' => 'Handstand Straddle Hold',
                'primary' => MuscleGroup::SHOULDERS,
                'secondary' => MuscleGroup::CORE,
                'level' => Levels::EXPERT,
                'difficulty' => 7,
                'description' => 'Handstand libre con piernas abiertas en posición de estrabismo (straddle). Requiere flexibilidad de cadera y equilibrio avanzado. Shape clásico del handstand artístico.',
                'disciplines' => ['calisthenics'],
            ],
        ];

        $created = 0;
        foreach ($required as $data) {
            $existing = $this->em->getRepository(Exercises::class)->findOneBy(['name' => $data['name']]);
            if ($existing) {
                $this->io->text("  ✓ {$data['name']} already exists");

                continue;
            }

            $ex = new Exercises();
            $ex->setName($data['name']);
            $ex->setPrimaryMuscleGroup($data['primary']);
            if (null !== $data['secondary']) {
                $ex->setSecondaryMuscleGroup($data['secondary']);
            }
            $ex->setLevel($data['level']);
            $ex->setDifficultyRating($data['difficulty']);
            $ex->setDescription($data['description']);
            $ex->setDisciplines($data['disciplines']);
            $this->em->persist($ex);
            ++$created;
            $this->io->text("  + Created {$data['name']}");
        }

        if ($created > 0) {
            $this->em->flush();
        }

        $this->io->text("Exercises: {$created} created");
    }

    private function deleteExisting(string $slug): void
    {
        $this->io->section('Deleting existing Handstand Balance Mastery data...');

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
        $program->setName('Handstand Balance Mastery');
        $program->setSlug($slug);
        $program->setDescription('Programa especializado de 6 niveles para dominar el equilibrio en handstand. Skill work diario, micro-progresiones, tests de dominio. Sin calendario: avanza por mastery.');
        $program->setDiscipline('calisthenics');
        $program->setTotalLevels(6);
        $program->setEstimatedDurationWeeks(24);
        $program->setDifficulty('intermediate');
        $program->setIsActive(true);

        $this->em->persist($program);
        $this->em->flush();

        $this->io->success("Program '{$slug}' created");

        return $program;
    }

    private function createLevel(TrainingProgram $program, int $levelNum): void
    {
        $this->io->section("Creating Level {$levelNum}");

        $meta = HandstandBalanceBlueprint::getLevelMeta($levelNum);

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
        $level->setIcon('accessibility_new');
        $level->setRequirementsSummary($meta['requirementsSummary']);
        $level->setSkillFocus($meta['skillFocus'] ?? null);
        $level->setProgramVersion('v1');
        $level->setCyclesCompleted(0);

        $this->applyLevelContent($level, $levelNum);

        $sessions = ['session_a', 'session_b', 'session_c', 'session_d'];
        $weeks = [0 => 'base', 1 => 'progression', 2 => 'intensification', 3 => 'deload'];

        foreach ($weeks as $weekNum => $phase) {
            foreach ($sessions as $sessionKey) {
                $data = HandstandBalanceBlueprint::getSessionData($levelNum, $sessionKey, $phase);
                $this->createTraining($level, $data, $weekNum, $sessionKey, $levelNum);
            }
        }

        $this->io->text("  Created Level {$levelNum} with 16 trainings");
    }

    private function createTraining(TrainingLevel $level, array $data, int $weekNum, string $sessionKey, int $levelNum): void
    {
        $training = new Training();
        $training->setName($data['name'].' - Fase '.($weekNum + 1));
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setIsBenchmark(false);
        $training->setIsCircuit('circuit' === $data['sessionType']);
        $training->setSessionType($data['sessionType']);
        $training->setTrainingLevel($level);
        $training->setWeekNumber($weekNum);
        $training->setDayKey($sessionKey);

        $this->em->persist($training);

        foreach ($data['blocks'] as $blockData) {
            $this->createTrainingRound($training, $blockData, $levelNum);
        }

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

        $note = HandstandBalanceContent::getExerciseNote($levelNum, $exData['name']);
        if ($note) {
            $config->setNotes($note);
        }

        $this->em->persist($config);
    }

    private function applyLevelContent(TrainingLevel $level, int $levelNum): void
    {
        $content = HandstandBalanceContent::getLevelContent($levelNum);

        $level->setTips($content['tips']);

        if (isset($content['testWeek']['tests']['requirements'])) {
            $level->setTestRequirements($content['testWeek']['tests']['requirements']);
        }

        foreach ($level->getWeekInfos() as $existing) {
            $level->removeWeekInfo($existing);
            $this->em->remove($existing);
        }

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
