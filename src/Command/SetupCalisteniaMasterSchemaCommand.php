<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:setup-calistenia-schema',
    description: 'Setup database schema for Calistenia Master program',
)]
class SetupCalisteniaMasterSchemaCommand extends Command
{
    private Connection $connection;

    private SymfonyStyle $io;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('Setting up Calistenia Master Schema');

        try {
            $this->setupTrainingProgramTable();
            $this->setupTrainingLevelTable();
            $this->addTrainingColumns();

            $this->io->success('Schema setup complete!');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->io->error('Error: '.$e->getMessage());

            return Command::FAILURE;
        }
    }

    private function setupTrainingProgramTable(): void
    {
        $this->io->section('Creating training_program table');

        $sql = 'CREATE TABLE IF NOT EXISTS training_program (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            description LONGTEXT DEFAULT NULL,
            discipline VARCHAR(50) NOT NULL,
            total_levels INT NOT NULL DEFAULT 12,
            estimated_duration_weeks INT DEFAULT NULL,
            difficulty VARCHAR(20) NOT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            image_url VARCHAR(500) DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

        $this->connection->executeStatement($sql);
        $this->io->success('training_program table created');
    }

    private function setupTrainingLevelTable(): void
    {
        $this->io->section('Creating training_level table');

        $sql = 'CREATE TABLE IF NOT EXISTS training_level (
            id INT AUTO_INCREMENT PRIMARY KEY,
            program_id INT NOT NULL,
            level_number INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            title VARCHAR(255) DEFAULT NULL,
            description LONGTEXT DEFAULT NULL,
            objective LONGTEXT DEFAULT NULL,
            estimated_duration_weeks INT DEFAULT 12,
            difficulty_rating INT DEFAULT NULL,
            color VARCHAR(20) DEFAULT NULL,
            icon VARCHAR(50) DEFAULT NULL,
            requirements_summary LONGTEXT DEFAULT NULL,
            is_locked_by_default TINYINT(1) NOT NULL DEFAULT 1,
            INDEX IDX_program_level (program_id, level_number),
            FOREIGN KEY (program_id) REFERENCES training_program(id) ON DELETE CASCADE,
            UNIQUE KEY unique_level_in_program (program_id, level_number)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

        $this->connection->executeStatement($sql);
        $this->io->success('training_level table created');
    }

    private function addTrainingColumns(): void
    {
        $this->io->section('Adding columns to training table');

        // Check if columns exist
        $columns = $this->connection->executeQuery('SHOW COLUMNS FROM training')->fetchAllAssociative();
        $columnNames = array_column($columns, 'Field');

        if (!\in_array('is_circuit', $columnNames, true)) {
            $this->connection->executeStatement('ALTER TABLE training ADD is_circuit TINYINT(1) DEFAULT 0');
            $this->io->text('Added is_circuit column');
        }

        if (!\in_array('training_level_id', $columnNames, true)) {
            $this->connection->executeStatement('ALTER TABLE training ADD training_level_id INT DEFAULT NULL');
            $this->connection->executeStatement('ALTER TABLE training ADD CONSTRAINT FK_training_level FOREIGN KEY (training_level_id) REFERENCES training_level(id) ON DELETE SET NULL');
            $this->connection->executeStatement('CREATE INDEX IDX_training_level ON training(training_level_id)');
            $this->io->text('Added training_level_id column with foreign key');
        }

        if (!\in_array('week_number', $columnNames, true)) {
            $this->connection->executeStatement('ALTER TABLE training ADD week_number INT DEFAULT NULL');
            $this->io->text('Added week_number column');
        }

        if (!\in_array('day_key', $columnNames, true)) {
            $this->connection->executeStatement('ALTER TABLE training ADD day_key VARCHAR(50) DEFAULT NULL');
            $this->io->text('Added day_key column');
        }

        $this->io->success('Training table updated');
    }
}
