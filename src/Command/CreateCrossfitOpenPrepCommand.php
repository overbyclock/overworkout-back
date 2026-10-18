<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Blueprint\CrossfitOpenPrepBlueprint;
use App\Command\Blueprint\CrossfitOpenPrepContent;
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
    name: 'app:create-crossfit-open-prep',
    description: 'Create or recreate CrossFit Open Prep program (8 weeks, 128 trainings)',
)]
class CreateCrossfitOpenPrepCommand extends Command
{
    private SymfonyStyle $io;

    /** @var array<string, int> ejercicios del blueprint no encontrados en el catálogo */
    private array $missingExercises = [];

    public function __construct(
        private EntityManagerInterface $em,
        private TrainingTimeCalculator $calculator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('delete-existing', 'd', InputOption::VALUE_NONE, 'Delete existing CrossFit Open Prep before creating')
            ->addOption('levels', 'l', InputOption::VALUE_OPTIONAL, 'Comma-separated levels to create (e.g. 1,2,3). Default: all 1-8', '1,2,3,4,5,6,7,8')
            ->addOption('slug', 's', InputOption::VALUE_OPTIONAL, 'Program slug', 'crossfit-open-prep');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('🔥 CrossFit Open Prep - Program Generator');

        $levels = array_map('intval', explode(',', (string) $input->getOption('levels')));
        $slug = (string) $input->getOption('slug');

        if ($input->getOption('delete-existing')) {
            $this->deleteExisting($slug);
        }

        $program = $this->getOrCreateProgram($slug);

        foreach ($levels as $levelNum) {
            $this->createLevel($program, $levelNum);
            $this->em->flush();
        }

        $this->printSummary($levels);

        return Command::SUCCESS;
    }

    private function printSummary(array $levels): void
    {
        if ([] !== $this->missingExercises) {
            $this->io->warning('OMISIONES / [SIN FICHA] (no encontrados en el catálogo):');
            foreach ($this->missingExercises as $name => $count) {
                $this->io->text("  - Ejercicio '{$name}' (referenciado {$count} veces)");
            }
        } else {
            $this->io->text('Omisiones: 0 (todo el contenido resuelto contra el catálogo)');
        }

        $this->io->success('CrossFit Open Prep creado correctamente. Niveles: '.implode(', ', $levels));
    }

    private function deleteExisting(string $slug): void
    {
        $this->io->section('Deleting existing CrossFit Open Prep data...');

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
        $program->setName('CrossFit Open Prep');
        $program->setSlug($slug);
        $program->setDescription('Programa de preparación de 8 semanas para el CrossFit Open: cada nivel es una semana con su Open-style WOD (engine, gimnástico denso, barbell cycling, chipper, AMRAP con carga, ladder, redo estratégico y peak). Estrategia Open: dividir desde el principio, tiebreak temprano y redos planificados. Sin calendario: avanza por mastery.');
        $program->setDiscipline('crossfit');
        $program->setTotalLevels(8);
        $program->setEstimatedDurationWeeks(8);
        $program->setDifficulty('advanced');
        $program->setIsActive(true);
        // Modo legacy (3 de 4 ítems): sin testEvaluationMode (mismo criterio que MU/False Grip mastery)

        $this->em->persist($program);
        $this->em->flush();

        $this->io->success("Program '{$slug}' created");

        return $program;
    }

    private function createLevel(TrainingProgram $program, int $levelNum): void
    {
        $this->io->section("Creating Level {$levelNum}");

        $meta = CrossfitOpenPrepBlueprint::getLevelMeta($levelNum);

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
            $level->setEstimatedDurationWeeks(1);
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
                $data = CrossfitOpenPrepBlueprint::getSessionData($levelNum, $sessionKey, $phase);
                $this->createTraining($level, $data, $weekNum, $sessionKey);
            }
        }

        $this->io->text("  Created Level {$levelNum} with 16 trainings");
    }

    private function createTraining(TrainingLevel $level, array $data, int $weekNum, string $sessionKey): void
    {
        $training = new Training();
        $training->setName($data['name'].' - Fase '.($weekNum + 1));
        $training->setDiscipline(Discipline::CROSSFIT);
        $training->setIsCircuit('circuit' === $data['sessionType']);
        $training->setSessionType($data['sessionType']);
        $training->setTrainingLevel($level);
        $training->setWeekNumber($weekNum);
        $training->setDayKey($sessionKey);

        $this->em->persist($training);

        foreach ($data['blocks'] as $blockData) {
            $this->createTrainingRound($training, $blockData, $sessionKey);
        }

        $duration = $this->calculator->calculate($training);
        $training->setEstimatedDurationMin($duration['min']);
        $training->setEstimatedDurationMax($duration['max']);
    }

    private function createTrainingRound(Training $training, array $blockData, string $sessionKey): void
    {
        $round = new TrainingRound();
        $round->setTraining($training);
        $training->addTrainingRound($round);
        $round->setSetsForRound($blockData['rounds']);
        $round->setRestBetweenRounds($blockData['restBetweenRounds']);
        $round->setBlockType($blockData['blockType']);
        $round->setName(CrossfitOpenPrepContent::getBlockName($blockData['blockType'], $sessionKey));
        $round->setDescription(CrossfitOpenPrepContent::getBlockDescription($blockData['blockType'], $sessionKey));

        if (isset($blockData['restAfterBlock'])) {
            $round->setRestAfterBlock($blockData['restAfterBlock']);
        }

        $this->em->persist($round);

        $restBetweenSets = $blockData['restBetweenSets'] ?? $blockData['restBetweenExercises'];
        foreach ($blockData['exercises'] as $exerciseData) {
            $this->createExerciseConfig($round, $exerciseData, $blockData['restBetweenExercises'], $restBetweenSets);
        }
    }

    private function createExerciseConfig(TrainingRound $round, array $exData, int $restBetweenExercises, int $restBetweenSets): void
    {
        $exercise = $this->em->getRepository(Exercises::class)->findOneBy(['name' => $exData['name']]);

        if (!$exercise) {
            $this->missingExercises[$exData['name']] = ($this->missingExercises[$exData['name']] ?? 0) + 1;
            $this->io->warning("  Exercise '{$exData['name']}' not found, skipping...");

            return;
        }

        $config = new TrainingExerciseConfiguration();
        $config->setTrainingRound($round);
        $round->addTrainingExerciseConfiguration($config);
        $config->setExercise($exercise);
        $config->setSets($exData['sets'] ?? 1);

        $reps = $exData['reps'];
        // Las dosis por tiempo ("30s", "60s") se modelan con maxTimeForReps; las de reps con reps.
        $timeMatch = preg_match('/(\d+)(?:-(\d+))?s/', $reps, $matches);
        if ($timeMatch) {
            $config->setReps(null);
            $maxTime = $matches[2] ?? $matches[1];
            $config->setMaxTimeForReps((int) $maxTime);
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

        $notes = CrossfitOpenPrepContent::getExerciseNotes((int) $round->getTraining()->getTrainingLevel()->getLevelNumber());
        if (isset($notes[$exData['name']])) {
            $config->setNotes($notes[$exData['name']]);
        }

        $this->em->persist($config);
    }

    private function applyLevelContent(TrainingLevel $level, int $levelNum): void
    {
        $content = CrossfitOpenPrepContent::getLevelContent($levelNum);

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
