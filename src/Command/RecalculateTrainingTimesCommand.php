<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Training;
use App\Service\TrainingTimeCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:recalculate-training-times',
    description: 'Recalculate estimated duration for all trainings',
)]
class RecalculateTrainingTimesCommand extends Command
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
        $io->title('Recalculating training estimated durations');

        $trainings = $this->entityManager->getRepository(Training::class)->findAll();
        $total = \count($trainings);

        if (0 === $total) {
            $io->warning('No trainings found');

            return Command::SUCCESS;
        }

        $io->progressStart($total);
        $updated = 0;

        foreach ($trainings as $training) {
            $result = $this->calculator->calculate($training);

            $training->setEstimatedDurationMin($result['min']);
            $training->setEstimatedDurationMax($result['max']);

            ++$updated;
            $io->progressAdvance();
        }

        $this->entityManager->flush();
        $io->progressFinish();

        $io->success(sprintf('Updated %d training(s)', $updated));

        return Command::SUCCESS;
    }
}
