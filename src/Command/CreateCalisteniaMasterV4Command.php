<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Blueprint\CalisteniaMasterBlueprintV4;
use App\Command\Blueprint\CalisteniaMasterContentV4;
use App\Entity\Achievement;
use App\Entity\Exercises;
use App\Entity\MobilityGate;
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
    name: 'app:create-calistenia-master-v4',
    description: 'Create or recreate Calistenia Master v4.0 program (15 levels, levels 1-3 detailed, mobility gates, achievements)',
)]
class CreateCalisteniaMasterV4Command extends Command
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
            ->addOption('delete-existing', 'd', InputOption::VALUE_NONE, 'Delete existing Calistenia Master v4 data (trainings, levels, gates, achievements) before creating')
            ->addOption('levels', 'l', InputOption::VALUE_OPTIONAL, 'Comma-separated levels to create (e.g. 1,2,3). Default: 1,2,3', '1,2,3')
            ->addOption('slug', 's', InputOption::VALUE_OPTIONAL, 'Program slug', 'calistenia-master-v4');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('🎯 Calistenia Master v4.0 - Program Generator');

        $levels = array_map('intval', explode(',', $input->getOption('levels')));
        $slug = $input->getOption('slug');

        if ($input->getOption('delete-existing')) {
            $this->deleteExisting($slug);
        }

        $program = $this->getOrCreateProgram($slug);
        $this->createMobilityGates($program);

        foreach ($levels as $levelNum) {
            $this->createLevel($program, $levelNum);
        }

        $this->em->flush();

        if ([] !== $this->missingExercises) {
            $this->io->warning('Ejercicios no encontrados en el catálogo (omitidos):');
            foreach ($this->missingExercises as $name => $count) {
                $this->io->text("  - {$name} (referenciado {$count} veces)");
            }
        }

        $this->io->success('Calistenia Master v4.0 created successfully! Levels: '.implode(', ', $levels));

        return Command::SUCCESS;
    }

    private function deleteExisting(string $slug): void
    {
        $this->io->section('Deleting existing Calistenia Master v4 data...');

        $program = $this->em->getRepository(TrainingProgram::class)->findOneBy(['slug' => $slug]);
        if (!$program) {
            $this->io->note('No existing program found');

            return;
        }

        $countTrainings = 0;
        $countLevels = 0;

        foreach ($this->em->getRepository(TrainingLevel::class)->findBy(['program' => $program]) as $level) {
            foreach ($level->getTrainings() as $training) {
                $this->em->remove($training);
                ++$countTrainings;
            }
            $this->em->remove($level);
            ++$countLevels;
        }

        $countGates = 0;
        foreach ($this->em->getRepository(MobilityGate::class)->findBy(['program' => $program]) as $gate) {
            $this->em->remove($gate);
            ++$countGates;
        }

        $countAchievements = 0;
        foreach ($this->em->getRepository(Achievement::class)->findBy(['program' => $program]) as $achievement) {
            $this->em->remove($achievement);
            ++$countAchievements;
        }

        $this->em->remove($program);
        $this->em->flush();
        $this->io->text("Deleted {$countLevels} levels, {$countTrainings} trainings, {$countGates} gates and {$countAchievements} achievements");
    }

    private function getOrCreateProgram(string $slug): TrainingProgram
    {
        $program = $this->em->getRepository(TrainingProgram::class)->findOneBy(['slug' => $slug]);
        $isNew = null === $program;

        if ($isNew) {
            $program = new TrainingProgram();
            $program->setSlug($slug);
            $this->em->persist($program);
        } else {
            $this->io->note("Program '{$slug}' already exists, updating");
        }

        $program->setName('Calistenia Master v4.0');
        $program->setDescription('Programa de 15 niveles + Olimpo para dominar la calistenia v4.0. Ciclos ilimitados con 4 fases (Base→Progresión→Intensificación→Recuperación), sesiones A/B/C/D con 5 bloques tipados (preparación, principal, salud postural, cadena posterior y descarga), tests con mínimos objetivos y puertas de movilidad (strict_gates).');
        $program->setDiscipline('calisthenics');
        $program->setTotalLevels(15);
        // El programa no usa calendario (progreso por fases y ciclos ilimitados): sin duración estimada.
        $program->setEstimatedDurationWeeks(null);
        $program->setDifficulty('beginner');
        $program->setIsActive(true);
        $program->setTestEvaluationMode(TrainingProgram::TEST_EVAL_STRICT_GATES);

        $this->em->flush();

        $this->io->success($isNew ? "Program '{$slug}' created" : "Program '{$slug}' updated");

        return $program;
    }

    /**
     * Crea/actualiza las puertas de movilidad G1-G6 ligadas al programa.
     * N1-N3 no las testean; se usan en niveles posteriores (ver description).
     */
    private function createMobilityGates(TrainingProgram $program): void
    {
        $this->io->section('Creating mobility gates G1-G6');

        foreach (CalisteniaMasterContentV4::getMobilityGates() as $data) {
            $gate = $this->em->getRepository(MobilityGate::class)->findOneBy([
                'program' => $program,
                'code' => $data['code'],
            ]);

            if (null === $gate) {
                $gate = new MobilityGate();
                $gate->setProgram($program);
                $gate->setCode($data['code']);
                $this->em->persist($gate);
            }

            $gate->setName($data['name']);
            $gate->setDescription($data['description']);
            $gate->setCriteria($data['criteria']);
        }

        $this->em->flush();
        $this->io->text('  6 mobility gates ready');
    }

    private function createLevel(TrainingProgram $program, int $levelNum): void
    {
        $this->io->section("Creating Level {$levelNum}");

        $meta = CalisteniaMasterBlueprintV4::getLevelMeta($levelNum);

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
        $level->setProgramVersion('v4');
        $level->setIsInfinite(16 === $levelNum);
        $level->setCyclesCompleted(0);

        // Aplicar contenido educativo (tips, weekInfos, test requirements)
        $this->applyLevelContent($level, $levelNum);

        $sessions = ['session_a', 'session_b', 'session_c', 'session_d'];
        $weeks = [0 => 'base', 1 => 'progression', 2 => 'intensification', 3 => 'deload'];

        foreach ($weeks as $weekNum => $phase) {
            foreach ($sessions as $sessionKey) {
                $data = CalisteniaMasterBlueprintV4::getSessionData($levelNum, $sessionKey, $phase);
                $this->createTraining($level, $data, $weekNum, $sessionKey, $levelNum);
            }
        }

        // Logros del nivel (idempotente por code)
        $this->createAchievements($program, $level, $levelNum);

        $this->io->text("  Created Level {$levelNum} with 16 trainings");
    }

    private function createTraining(TrainingLevel $level, array $data, int $weekNum, string $sessionKey, int $levelNum): void
    {
        $training = new Training();
        $training->setName($data['name'].' - Fase '.($weekNum + 1));
        $training->setDiscipline(Discipline::CALISTHENICS);
        // strength sessions are NOT circuits; circuit sessions ARE circuits
        $training->setIsCircuit('circuit' === $data['sessionType']);
        $training->setSessionType($data['sessionType']);
        $training->setTrainingLevel($level);
        $training->setWeekNumber($weekNum);
        $training->setDayKey($sessionKey);

        $this->em->persist($training);

        foreach ($data['blocks'] as $blockData) {
            $this->createTrainingRound($training, $blockData, $levelNum, $sessionKey, $data['goal'] ?? null);
        }

        // Auto-calculate duration
        $duration = $this->calculator->calculate($training);
        $training->setEstimatedDurationMin($duration['min']);
        $training->setEstimatedDurationMax($duration['max']);
    }

    private function createTrainingRound(Training $training, array $blockData, int $levelNum, string $sessionKey, ?string $sessionGoal): void
    {
        $round = new TrainingRound();
        $round->setTraining($training);
        $training->addTrainingRound($round);
        $round->setBlockType($blockData['blockType']);
        // Nombre y descripción del bloque (convención v4 por tipo de bloque y sesión).
        // En el bloque principal la descripción es el objetivo de la sesión.
        $round->setName(CalisteniaMasterContentV4::getBlockName($blockData['blockType'], $sessionKey));
        $description = CalisteniaMasterContentV4::getBlockDescription($blockData['blockType'], $sessionKey);
        if (TrainingRound::BLOCK_PRINCIPAL === $blockData['blockType'] && null !== $sessionGoal) {
            $description = $sessionGoal;
        }
        $round->setDescription($description);
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
        // Las dosis "5/lado" contienen letras pero son repeticiones: se decide por el patrón de tiempo real.
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

        // Aplicar nota tecnica del contenido educativo
        $note = CalisteniaMasterContentV4::getExerciseNote($levelNum, $exData['name']);
        if ($note) {
            $config->setNotes($note);
        }

        $this->em->persist($config);
    }

    private function applyLevelContent(TrainingLevel $level, int $levelNum): void
    {
        $content = CalisteniaMasterContentV4::getLevelContent($levelNum);

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

    /**
     * Crea/actualiza los logros del nivel (idempotente: busca por code).
     */
    private function createAchievements(TrainingProgram $program, TrainingLevel $level, int $levelNum): void
    {
        $created = 0;
        $updated = 0;

        foreach (CalisteniaMasterContentV4::getAchievements($levelNum) as $data) {
            $achievement = $this->em->getRepository(Achievement::class)->findOneBy(['code' => $data['code']]);

            if (null === $achievement) {
                $achievement = new Achievement();
                $achievement->setCode($data['code']);
                $this->em->persist($achievement);
                ++$created;
            } else {
                ++$updated;
            }

            $achievement->setName($data['name']);
            $achievement->setDescription($data['description']);
            $achievement->setCategory($data['category']);
            $achievement->setIcon($data['icon']);
            $achievement->setColor($level->getColor());
            $achievement->setXpReward($data['xpReward']);
            $achievement->setProgram($program);
            $achievement->setLevel($level);
            $achievement->setTier($data['tier']);
            $achievement->setType($data['type']);
            $achievement->setSpecialCelebration($data['specialCelebration']);
            $achievement->setRequirementType($data['requirementType']);
            $achievement->setRequirementKey($data['requirementKey']);
            $achievement->setRequirementValue(null);
        }

        $this->io->text(\sprintf('  Achievements: %d created, %d updated', $created, $updated));
    }
}
