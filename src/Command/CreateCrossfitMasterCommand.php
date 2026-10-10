<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Blueprint\CrossfitMasterBlueprint;
use App\Command\Blueprint\CrossfitMasterContent;
use App\Entity\Achievement;
use App\Entity\Benchmark;
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
    name: 'app:create-crossfit-master',
    description: 'Create or recreate CrossFit Master v1 program (16 levels, levels 1-3 detailed, benchmark WODs, mobility gates, achievements)',
)]
class CreateCrossfitMasterCommand extends Command
{
    private SymfonyStyle $io;

    /** @var array<string, int> ejercicios del blueprint no encontrados en el catálogo */
    private array $missingExercises = [];

    /** @var array<string, int> benchmarks del documento no encontrados en el catálogo */
    private array $missingBenchmarks = [];

    /** @var array<int, string> benchmarks embebidos (id => nombre) */
    private array $embeddedBenchmarks = [];

    /** @var array{min: int, max: int}|null duración acumulada del nivel en curso (min-max de sesiones, en segundos) */
    private ?array $levelDuration = null;

    public function __construct(
        private EntityManagerInterface $em,
        private TrainingTimeCalculator $calculator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('delete-existing', 'd', InputOption::VALUE_NONE, 'Delete existing CrossFit Master data (trainings, levels, gates, achievements) before creating')
            ->addOption('levels', 'l', InputOption::VALUE_OPTIONAL, 'Comma-separated levels to create (e.g. 1,2,3). Default: 1,2,3', '1,2,3')
            ->addOption('slug', 's', InputOption::VALUE_OPTIONAL, 'Program slug', 'crossfit-master');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('🏋️ CrossFit Master v1 - Program Generator');

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

        $this->printSummary($levels);

        return Command::SUCCESS;
    }

    private function printSummary(array $levels): void
    {
        if ([] !== $this->embeddedBenchmarks) {
            $this->io->section('Benchmarks embebidos');
            foreach ($this->embeddedBenchmarks as $id => $name) {
                $this->io->text("  - #{$id} {$name}");
            }
        }

        if ([] !== $this->missingExercises || [] !== $this->missingBenchmarks) {
            $this->io->warning('OMISIONES (no encontrados en el catálogo):');
            foreach ($this->missingExercises as $name => $count) {
                $this->io->text("  - Ejercicio '{$name}' (referenciado {$count} veces)");
            }
            foreach ($this->missingBenchmarks as $name => $count) {
                $this->io->text("  - Benchmark '{$name}' (referenciado {$count} veces)");
            }
        } else {
            $this->io->text('Omisiones: 0 (todo el contenido resuelto contra el catálogo)');
        }

        $this->io->success('CrossFit Master creado correctamente. Niveles: '.implode(', ', $levels));
    }

    private function deleteExisting(string $slug): void
    {
        $this->io->section('Deleting existing CrossFit Master data...');

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

        $program->setName('CrossFit Master v1');
        $program->setDescription('Programa de 16 niveles de CrossFit: ciclos ilimitados con 4 fases (Base→Progresión→Intensificación→Recuperación), sesiones A/B/C/D con 5 bloques tipados (preparación, fuerza/skill, WOD, prehab postural y descarga), tests con mínimos y objetivos, benchmarks del catálogo embebidos como WODs y puertas de movilidad CF1-CF7.');
        $program->setDiscipline(Discipline::CROSSFIT->value);
        $program->setTotalLevels(16);
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
     * Crea/actualiza las puertas de movilidad CF1-CF7 ligadas al programa.
     * N1 no evalúa ninguna; N2 evalúa CF5; N3 evalúa CF1 y CF4; el resto queda
     * definida para lotes N4-N6 y posteriores.
     */
    private function createMobilityGates(TrainingProgram $program): void
    {
        $this->io->section('Creating mobility gates CF1-CF7');

        foreach (CrossfitMasterContent::getMobilityGates() as $data) {
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
        $this->io->text('  7 mobility gates ready');
    }

    private function createLevel(TrainingProgram $program, int $levelNum): void
    {
        $this->io->section("Creating Level {$levelNum}");

        $meta = CrossfitMasterBlueprint::getLevelMeta($levelNum);

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
        $level->setProgramVersion('v1');
        $level->setIsInfinite(false);
        $level->setCyclesCompleted(0);

        // Aplicar contenido educativo (tips, weekInfos, test requirements)
        $this->applyLevelContent($level, $levelNum);

        $sessions = ['session_a', 'session_b', 'session_c', 'session_d'];
        $weeks = [0 => 'base', 1 => 'progression', 2 => 'intensification', 3 => 'deload'];

        $totalRounds = 0;
        $this->levelDuration = ['min' => PHP_INT_MAX, 'max' => 0];
        foreach ($weeks as $weekNum => $phase) {
            foreach ($sessions as $sessionKey) {
                $data = CrossfitMasterBlueprint::getSessionData($levelNum, $sessionKey, $phase);
                $totalRounds += $this->createTraining($level, $data, $weekNum, $sessionKey, $levelNum);
            }
        }

        // Logros del nivel (idempotente por code)
        $this->createAchievements($program, $level, $levelNum);

        $duration = [
            'min' => (int) round($this->levelDuration['min'] / 60),
            'max' => (int) round($this->levelDuration['max'] / 60),
        ];
        $this->levelDuration = null;
        $this->io->text("  Created Level {$levelNum} with 16 trainings, {$totalRounds} rounds");
        $this->io->text(\sprintf('  Estimated duration: %d-%d min (min-max across sessions)', $duration['min'], $duration['max']));
    }

    /**
     * Acumula la duración calculada de una sesión en el resumen del nivel.
     *
     * @param array{min: int, max: int} $duration duración de la sesión en segundos
     */
    private function accumulateDuration(array $duration): void
    {
        if (null === $this->levelDuration) {
            return;
        }

        if ($duration['min'] > 0) {
            $this->levelDuration['min'] = min($this->levelDuration['min'], $duration['min']);
        }
        $this->levelDuration['max'] = max($this->levelDuration['max'], $duration['max']);
    }

    private function createTraining(TrainingLevel $level, array $data, int $weekNum, string $sessionKey, int $levelNum): int
    {
        $training = new Training();
        $training->setName($data['name'].' - Fase '.($weekNum + 1));
        $training->setDiscipline(Discipline::CROSSFIT);
        // las sesiones C (metcon) son circuitos; el resto, series tradicionales
        $training->setIsCircuit('circuit' === $data['sessionType']);
        $training->setSessionType($data['sessionType']);
        $training->setTrainingLevel($level);
        $training->setWeekNumber($weekNum);
        $training->setDayKey($sessionKey);

        $this->em->persist($training);

        $roundCount = 0;
        foreach ($data['blocks'] as $blockData) {
            $this->createTrainingRound($training, $blockData, $levelNum, $sessionKey, $data['goal'] ?? null);
            ++$roundCount;
        }

        // Auto-calculate duration
        $duration = $this->calculator->calculate($training);
        $training->setEstimatedDurationMin($duration['min']);
        $training->setEstimatedDurationMax($duration['max']);
        $this->accumulateDuration($duration);

        return $roundCount;
    }

    private function createTrainingRound(Training $training, array $blockData, int $levelNum, string $sessionKey, ?string $sessionGoal): void
    {
        $round = new TrainingRound();
        $round->setTraining($training);
        $training->addTrainingRound($round);
        $round->setBlockType($blockData['blockType']);
        // Nombre y descripción del bloque (convención por tipo de bloque y sesión).
        $round->setName(CrossfitMasterContent::getBlockName($blockData['blockType'], $sessionKey));
        $description = CrossfitMasterContent::getBlockDescription($blockData['blockType'], $sessionKey);

        if (TrainingRound::BLOCK_FUERZA_SKILL === $blockData['blockType']) {
            $tip = CrossfitMasterContent::getSessionTip($levelNum, $sessionKey);
            $description = $sessionGoal.($tip ? '. '.$tip : '');
        }

        if (isset($blockData['benchmark'])) {
            // El round ES un WOD del catálogo: ignora configs; solo referencia + escalado
            $benchmark = $this->resolveBenchmark($blockData['benchmark']);
            if (null === $benchmark) {
                $this->missingBenchmarks[$blockData['benchmark']] = ($this->missingBenchmarks[$blockData['benchmark']] ?? 0) + 1;
                $this->io->warning("  Benchmark '{$blockData['benchmark']}' not found, skipping round...");
                $training->removeTrainingRound($round);

                return;
            }

            $round->setBenchmark($benchmark);
            $this->embeddedBenchmarks[$benchmark->getId()] = $benchmark->getName();
            $description = ($blockData['benchmarkNote'] ?? $description);
        } elseif (isset($blockData['description'])) {
            $description = $blockData['description'];
        }

        $round->setDescription($description);
        $round->setSetsForRound($blockData['rounds']);
        if ($blockData['restBetweenRounds'] > 0) {
            $round->setRestBetweenRounds($blockData['restBetweenRounds']);
        }

        if (isset($blockData['restAfterBlock'])) {
            $round->setRestAfterBlock($blockData['restAfterBlock']);
        }

        $this->em->persist($round);

        if (isset($blockData['benchmark'])) {
            return;
        }

        $restBetweenSets = $blockData['restBetweenSets'] ?? $blockData['restBetweenExercises'];
        foreach ($blockData['exercises'] as $exerciseData) {
            $this->createExerciseConfig($round, $exerciseData, $blockData['restBetweenExercises'], $restBetweenSets);
        }
    }

    private function resolveBenchmark(string $name): ?Benchmark
    {
        return $this->em->getRepository(Benchmark::class)->findOneBy(['name' => $name]);
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
        // Las dosis por tiempo ("30s", "60s") se modelan con maxTimeForReps;
        // las de distancia ("20m") y las de repeticiones ("8", "8/lado") con reps.
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

        $this->em->persist($config);
    }

    private function applyLevelContent(TrainingLevel $level, int $levelNum): void
    {
        $content = CrossfitMasterContent::getLevelContent($levelNum);

        // Tips
        $level->setTips($content['tips']);

        // Test requirements (exit criteria); los benchmarkId se resuelven ahora
        if (isset($content['testWeek']['tests']['requirements'])) {
            $requirements = $content['testWeek']['tests']['requirements'];
            foreach ($requirements as $index => $requirement) {
                if (isset($requirement['benchmarkName'])) {
                    $benchmark = $this->resolveBenchmark($requirement['benchmarkName']);
                    if (null === $benchmark) {
                        $this->missingBenchmarks[$requirement['benchmarkName']] = ($this->missingBenchmarks[$requirement['benchmarkName']] ?? 0) + 1;
                        $this->io->warning("  Benchmark '{$requirement['benchmarkName']}' not found in test requirements, skipping item...");
                        unset($requirements[$index]);

                        continue;
                    }
                    $requirement['benchmarkId'] = $benchmark->getId();
                    $this->embeddedBenchmarks[$benchmark->getId()] = $benchmark->getName();
                    unset($requirement['benchmarkName']);
                    $requirements[$index] = $requirement;
                }
            }
            $level->setTestRequirements(array_values($requirements));
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

        foreach (CrossfitMasterContent::getAchievements($levelNum) as $data) {
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
