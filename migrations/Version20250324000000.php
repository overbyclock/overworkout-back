<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250324000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Calistenia Master Level 1 structure with training relations';
    }

    public function up(Schema $schema): void
    {
        // Añadir columnas a training para relacionar con training_level
        $this->addSql('ALTER TABLE training ADD training_level_id INT DEFAULT NULL, ADD week_number INT DEFAULT NULL, ADD day_key VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD CONSTRAINT FK_8003A7A59A7E9C6 FOREIGN KEY (training_level_id) REFERENCES training_level (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_8003A7A59A7E9C6 ON training (training_level_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE training DROP FOREIGN KEY FK_8003A7A59A7E9C6');
        $this->addSql('DROP INDEX IDX_8003A7A59A7E9C6 ON training');
        $this->addSql('ALTER TABLE training DROP training_level_id, DROP week_number, DROP day_key');
    }
}
