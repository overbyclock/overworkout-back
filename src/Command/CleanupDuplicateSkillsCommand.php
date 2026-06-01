<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:cleanup-duplicate-skills',
    description: 'Elimina skills duplicados por programa y nombre',
)]
class CleanupDuplicateSkillsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $conn = $this->entityManager->getConnection();

        $conn->executeStatement('
            DELETE s1 FROM training_skills s1
            INNER JOIN training_skills s2
            WHERE s1.id > s2.id
            AND s1.program_id = s2.program_id
            AND s1.name = s2.name
        ');

        $output->writeln('Skills duplicados eliminados');

        return Command::SUCCESS;
    }
}
