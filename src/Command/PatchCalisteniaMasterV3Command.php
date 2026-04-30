<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Blueprint\CalisteniaMasterBlueprintV3;
use App\Command\Blueprint\CalisteniaMasterContentV3;
use App\Entity\Discipline;
use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingRound;
use App\Entity\TrainingWeekInfo;
use App\Service\TrainingTimeCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:patch-calistenia-master-v3', description: 'Actualiza trainings de niveles del programa v3 sin borrar progreso de usuarios')]
class PatchCalisteniaMasterV3Command extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private TrainingTimeCalculator $calculator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('levels', null, InputOption::VALUE_REQUIRED, 'Niveles a actualizar (ej: 5,6,7,10)', '7,8,9,10,11,12');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $levelsOption = $input->getOption('levels');
        $targetLevels = array_map('intval', explode(',', $levelsOption));
        $io->title('Patch Calistenia Master v3.0 — Niveles ' . implode(', ', $targetLevels));

        $program = $this->em->getRepository(TrainingProgram::class)->findOneBy(['slug' => 'calisthenia-master-v3']);
        if (!$program) {
            $io->error('Programa calisthenia-master-v3 no encontrado');
            return Command::FAILURE;
        }

        $levels = $this->em->getRepository(TrainingLevel::class)->findBy([
            'program' => $program,
        ], ['levelNumber' => 'ASC']);

        $patched = 0;
        /** @var TrainingLevel $level */
        foreach ($levels as $level) {
            $levelNum = $level->getLevelNumber();
            if (!in_array($levelNum, $targetLevels, true)) {
                continue;
            }

            $io->section("Nivel {$levelNum}");

            $sessionsToUpdate = ['session_a', 'session_b', 'session_c', 'session_d'];

            foreach ($sessionsToUpdate as $sessionKey) {
                $weeks = [0 => 'base', 1 => 'progression', 2 => 'intensification', 3 => 'deload'];
                foreach ($weeks as $weekNum => $phase) {
                    $training = $this->em->getRepository(Training::class)->findOneBy([
                        'trainingLevel' => $level,
                        'dayKey' => $sessionKey,
                        'weekNumber' => $weekNum,
                    ]);

                    if (!$training) {
                        $io->warning("  Training no encontrado: {$sessionKey} week {$weekNum}");
                        continue;
                    }

                    // Obtener datos nuevos del blueprint
                    $data = CalisteniaMasterBlueprintV3::getSessionData($levelNum, $sessionKey, $phase);

                    // Actualizar propiedades del training
                    $training->setName($data['name'] . ' - Fase ' . $weekNum);
                    $training->setIsCircuit($data['sessionType'] === 'circuit');
                    $training->setSessionType($data['sessionType']);

                    // Eliminar rondas existentes (cascade remove)
                    foreach ($training->getTrainingRounds()->toArray() as $round) {
                        $training->removeTrainingRound($round);
                    }

                    // Crear nuevas rondas
                    foreach ($data['blocks'] as $blockData) {
                        $this->createTrainingRound($training, $blockData, $levelNum);
                    }

                    // Recalcular duración
                    $duration = $this->calculator->calculate($training);
                    $training->setEstimatedDurationMin($duration['min']);
                    $training->setEstimatedDurationMax($duration['max']);

                    $io->text("  Actualizado: {$data['name']} (Fase {$weekNum})");
                    $patched++;
                }
            }

            // Actualizar contenido educativo del nivel
            $this->applyLevelContent($level, $levelNum);
        }

        $this->em->flush();
        $io->success("Patch completado. {$patched} trainings actualizados.");

        return Command::SUCCESS;
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

        $note = CalisteniaMasterContentV3::getExerciseNote($levelNum, $exData['name']);
        if ($note) {
            $config->setNotes($note);
        }

        $this->em->persist($config);
    }

    private function applyLevelContent(TrainingLevel $level, int $levelNum): void
    {
        $content = CalisteniaMasterContentV3::getLevelContent($levelNum);
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
            $level->addWeekInfo($weekInfo);
            $this->em->persist($weekInfo);
        }
    }
}
