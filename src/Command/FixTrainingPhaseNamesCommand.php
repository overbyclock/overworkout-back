<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Training;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fix-training-phase-names',
    description: 'Corrige nombres de fase 0-3 a 1-4 en trainings v3 (sin tocar weekNumber)',
)]
class FixTrainingPhaseNamesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('🔧 Fix: Fase 0→1, 1→2, 2→3, 3→4 en trainings v3');

        $program = $this->em->getRepository(TrainingProgram::class)->findOneBy(['slug' => 'calisthenia-master-v3']);
        if (!$program) {
            $io->error('Programa calisthenia-master-v3 no encontrado');

            return Command::FAILURE;
        }

        $levels = $this->em->getRepository(TrainingLevel::class)->findBy(['program' => $program]);
        if (empty($levels)) {
            $io->error('No se encontraron niveles para el programa v3');

            return Command::FAILURE;
        }

        $updated = 0;

        foreach ($levels as $level) {
            $trainings = $this->em->getRepository(Training::class)->findBy([
                'trainingLevel' => $level,
            ]);

            foreach ($trainings as $training) {
                $name = $training->getName() ?? '';
                $weekNumber = $training->getWeekNumber();

                if (null === $weekNumber || $weekNumber < 0 || $weekNumber > 3) {
                    continue;
                }

                $expectedPhase = $weekNumber + 1;
                $newName = preg_replace('/ - Fase \d+$/', ' - Fase '.$expectedPhase, $name);

                if ($newName !== $name) {
                    $training->setName($newName);
                    ++$updated;
                    $io->text("  {$name} → {$newName}");
                }
            }
        }

        $this->em->flush();
        $io->success("Actualizados {$updated} trainings.");

        return Command::SUCCESS;
    }
}
