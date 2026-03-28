<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Exercises;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:list-exercises',
    description: 'Lista todos los ejercicios de la base de datos',
)]
class ListExercisesCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exercises = $this->entityManager->getRepository(Exercises::class)->findAll();

        $output->writeln("=== EJERCICIOS EN LA BASE DE DATOS ===\n");
        $output->writeln(\sprintf('%-5s | %-40s | %-15s | %-20s', 'ID', 'Nombre', 'Nivel', 'Grupo Muscular'));
        $output->writeln(str_repeat('-', 90));

        foreach ($exercises as $ex) {
            $output->writeln(\sprintf(
                '%-5s | %-40s | %-15s | %-20s',
                $ex->getId(),
                substr($ex->getName(), 0, 38),
                $ex->getLevel()?->value ?? 'N/A',
                $ex->getPrimaryMuscleGroup()?->value ?? 'N/A'
            ));
        }

        $output->writeln("\n=== TOTAL: ".\count($exercises).' ejercicios ===');

        // Filtrar por nivel BEGINNER
        $beginnerExercises = array_filter($exercises, fn ($ex) => 'beginner' === $ex->getLevel()?->value);
        $output->writeln("\n=== EJERCICIOS NIVEL BEGINNER (".\count($beginnerExercises).") ===\n");

        foreach ($beginnerExercises as $ex) {
            $output->writeln(\sprintf(
                'ID: %-5s | %-40s | %s',
                $ex->getId(),
                $ex->getName(),
                $ex->getPrimaryMuscleGroup()?->value ?? 'N/A'
            ));
        }

        return Command::SUCCESS;
    }
}
