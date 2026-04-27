<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Training;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Service\TrainingTimeCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fix-calistenia-master-program',
    description: 'Fix names, unify structure and adjust rounds for Calistenia Master levels 1-12',
)]
class FixCalisteniaMasterProgramCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TrainingTimeCalculator $calculator,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Fixing Calistenia Master Program (Levels 1-12)');

        $program = $this->entityManager->getRepository(TrainingProgram::class)->findOneBy(['slug' => 'calisthenia-master']);
        if (!$program) {
            $io->error('Program "calistenia-master" not found');

            return Command::FAILURE;
        }

        $levels = $this->entityManager->getRepository(TrainingLevel::class)
            ->findBy(['program' => $program], ['levelNumber' => 'ASC']);

        if (\count($levels) !== 12) {
            $io->warning(sprintf('Expected 12 levels, found %d', \count($levels)));
        }

        $totalUpdated = 0;

        foreach ($levels as $level) {
            $levelNum = $level->getLevelNumber();
            $targetRounds = $this->getTargetRoundsForLevel($levelNum);

            $io->section(sprintf('Level %d: %s', $levelNum, $level->getName()));

            $trainings = $level->getTrainings();
            $updatedInLevel = 0;

            foreach ($trainings as $training) {
                $originalName = $training->getName();
                $fixedName = $this->fixTrainingName($originalName);

                if ($fixedName !== $originalName) {
                    $training->setName($fixedName);
                }

                // Ajustar rondas en los TrainingRounds
                foreach ($training->getTrainingRounds() as $round) {
                    $round->setSetsForRound($targetRounds);
                }

                // Recalcular duración
                $result = $this->calculator->calculate($training);
                $training->setEstimatedDurationMin($result['min']);
                $training->setEstimatedDurationMax($result['max']);

                ++$updatedInLevel;
                ++$totalUpdated;
            }

            $io->text(sprintf('Updated %d training(s) -> %d rounds', $updatedInLevel, $targetRounds));
        }

        $this->entityManager->flush();

        $io->success(sprintf('Fixed %d training(s) across %d level(s)', $totalUpdated, \count($levels)));

        return Command::SUCCESS;
    }

    private function getTargetRoundsForLevel(int $levelNumber): int
    {
        return match (true) {
            $levelNumber <= 3 => 3,
            $levelNumber <= 6 => 4,
            $levelNumber <= 9 => 5,
            default => 6,
        };
    }

    private function fixTrainingName(string $name): string
    {
        // Normalizar prefijo de día
        $name = preg_replace('/^Dia\s+(\d+):/iu', 'Día $1:', $name);

        // Eliminar sufijos de skill/unilateral/tests
        $name = preg_replace('/\s*\+\s*Skill(s)?/iu', '', $name);
        $name = preg_replace('/\s*\+\s*Unilateral/iu', '', $name);
        $name = preg_replace('/\s*\+\s*Tests/iu', '', $name);

        // Normalizar acentos en fases
        $name = str_replace(
            ['Progresion', 'progresion', 'Intensificacion', 'intensificacion'],
            ['Progresión', 'Progresión', 'Intensificación', 'Intensificación'],
            $name
        );

        // Si quedó "Core - Semana" sin "Base", añadirlo
        $name = preg_replace('/Core\s+-\s+Semana/iu', 'Core Base - Semana', $name);

        // Limpiar dobles espacios
        $name = preg_replace('/\s+/', ' ', $name);

        return trim($name);
    }
}
