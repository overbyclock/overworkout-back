<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260901124041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Expande el CHECK de training_levels.difficulty_rating a 1-16 para el nivel Olimpo';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE training_levels DROP CHECK training_levels_chk_1');
        $this->addSql('ALTER TABLE training_levels ADD CONSTRAINT training_levels_chk_1 CHECK (difficulty_rating BETWEEN 1 AND 16)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE training_levels DROP CHECK training_levels_chk_1');
        $this->addSql('ALTER TABLE training_levels ADD CONSTRAINT training_levels_chk_1 CHECK (difficulty_rating BETWEEN 1 AND 15)');
    }
}
