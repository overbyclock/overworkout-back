<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Expand difficulty_rating range from 1-5 to 1-10 and update Calistenia Master levels.
 */
final class Version20260424122500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Expand training_levels difficulty_rating to 1-10 and update Calistenia Master ratings';
    }

    public function up(Schema $schema): void
    {
        // 1. Remove old CHECK constraint (1-5)
        $this->addSql('ALTER TABLE training_levels DROP CHECK training_levels_chk_1');

        // 2. Add new CHECK constraint (1-10)
        $this->addSql('ALTER TABLE training_levels ADD CONSTRAINT training_levels_chk_1 CHECK (difficulty_rating BETWEEN 1 AND 10)');

        // 3. Update difficulty ratings to real 1-10 scale for Calistenia Master program
        $updates = [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10,
            11 => 10,
            12 => 10,
        ];

        foreach ($updates as $level => $rating) {
            $this->addSql(
                'UPDATE training_levels SET difficulty_rating = ? WHERE level_number = ? AND program_id = (SELECT id FROM training_programs WHERE slug = ?)',
                [$rating, $level, 'calistenia-master']
            );
        }
    }

    public function down(Schema $schema): void
    {
        // 1. Revert difficulty ratings to compressed 1-5 scale
        $downgrades = [
            1 => 1,
            2 => 1,
            3 => 2,
            4 => 2,
            5 => 3,
            6 => 3,
            7 => 4,
            8 => 4,
            9 => 5,
            10 => 5,
            11 => 5,
            12 => 5,
        ];

        foreach ($downgrades as $level => $rating) {
            $this->addSql(
                'UPDATE training_levels SET difficulty_rating = ? WHERE level_number = ? AND program_id = (SELECT id FROM training_programs WHERE slug = ?)',
                [$rating, $level, 'calistenia-master']
            );
        }

        // 2. Remove expanded CHECK constraint
        $this->addSql('ALTER TABLE training_levels DROP CHECK training_levels_chk_1');

        // 3. Restore original CHECK constraint (1-5)
        $this->addSql('ALTER TABLE training_levels ADD CONSTRAINT training_levels_chk_1 CHECK (difficulty_rating BETWEEN 1 AND 5)');
    }
}
