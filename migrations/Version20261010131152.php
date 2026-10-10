<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261010131152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_round ADD benchmark_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training_round ADD CONSTRAINT FK_2A346059E80D127B FOREIGN KEY (benchmark_id) REFERENCES benchmark (id)');
        $this->addSql('CREATE INDEX IDX_2A346059E80D127B ON training_round (benchmark_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_round DROP FOREIGN KEY FK_2A346059E80D127B');
        $this->addSql('DROP INDEX IDX_2A346059E80D127B ON training_round');
        $this->addSql('ALTER TABLE training_round DROP benchmark_id');
    }
}
