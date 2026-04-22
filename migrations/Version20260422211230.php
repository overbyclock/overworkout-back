<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260422211230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE training_week_infos (id INT AUTO_INCREMENT NOT NULL, week_number INT NOT NULL, name VARCHAR(255) NOT NULL, focus VARCHAR(255) DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, intensity VARCHAR(50) DEFAULT NULL, training_level_id INT NOT NULL, INDEX IDX_9FDFF188B8D45830 (training_level_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE training_week_infos ADD CONSTRAINT FK_9FDFF188B8D45830 FOREIGN KEY (training_level_id) REFERENCES training_levels (id)');
        $this->addSql('ALTER TABLE training_exercise_configuration ADD notes LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE training_levels ADD tips JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_week_infos DROP FOREIGN KEY FK_9FDFF188B8D45830');
        $this->addSql('DROP TABLE training_week_infos');
        $this->addSql('ALTER TABLE training_exercise_configuration DROP notes');
        $this->addSql('ALTER TABLE training_levels DROP tips');
    }
}
