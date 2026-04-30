<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cleanup-old-program-versions',
    description: 'Elimina versiones antiguas del programa (v1 y v2) dejando solo v3',
)]
class CleanupOldProgramVersionsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('🧹 Eliminando versiones antiguas (v1 y v2)');

        $slugs = ['calisthenia-master', 'calisthenia-master-v2'];
        $totalRemovedTrainings = 0;
        $totalRemovedLevels = 0;
        $totalRemovedPrograms = 0;

        foreach ($slugs as $slug) {
            $program = $this->em->getRepository(TrainingProgram::class)->findOneBy(['slug' => $slug]);
            if (!$program) {
                $io->warning("Programa {$slug} no encontrado, saltando");

                continue;
            }

            $io->section("Procesando: {$slug} (ID: {$program->getId()})");

            $levels = $this->em->getRepository(TrainingLevel::class)->findBy(['program' => $program]);
            $levelCount = 0;
            $trainingCount = 0;

            foreach ($levels as $level) {
                // Eliminar trainings (cascade remove borra rounds y configs)
                foreach ($level->getTrainings()->toArray() as $training) {
                    $level->removeTraining($training);
                    $this->em->remove($training);
                    ++$trainingCount;
                }

                // Eliminar week infos
                foreach ($level->getWeekInfos()->toArray() as $weekInfo) {
                    $level->removeWeekInfo($weekInfo);
                    $this->em->remove($weekInfo);
                }

                $this->em->remove($level);
                ++$levelCount;
            }

            $this->em->remove($program);

            $this->em->flush();

            $io->text("  Eliminados: {$trainingCount} trainings, {$levelCount} niveles");
            $totalRemovedTrainings += $trainingCount;
            $totalRemovedLevels += $levelCount;
            ++$totalRemovedPrograms;
        }

        $io->success("Limpieza completada: {$totalRemovedPrograms} programas, {$totalRemovedLevels} niveles, {$totalRemovedTrainings} trainings eliminados.");

        return Command::SUCCESS;
    }
}
