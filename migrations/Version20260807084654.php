<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Expand difficulty_rating range from 1-10 to 1-15 for Calistenia Master v4 (15 niveles).
 */
final class Version20260807084654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Expand training_levels difficulty_rating CHECK to 1-15 (Calistenia Master v4 tiene 15 niveles)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE training_levels DROP CHECK training_levels_chk_1');
        $this->addSql('ALTER TABLE training_levels ADD CONSTRAINT training_levels_chk_1 CHECK (difficulty_rating BETWEEN 1 AND 15)');
    }

    public function down(Schema $schema): void
    {
        // Capar primero los ratings por encima de 10 para poder restaurar el CHECK 1-10
        $this->addSql('UPDATE training_levels SET difficulty_rating = 10 WHERE difficulty_rating > 10');
        $this->addSql('ALTER TABLE training_levels DROP CHECK training_levels_chk_1');
        $this->addSql('ALTER TABLE training_levels ADD CONSTRAINT training_levels_chk_1 CHECK (difficulty_rating BETWEEN 1 AND 10)');
    }
}
