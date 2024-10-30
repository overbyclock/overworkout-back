<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030205346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_exercise_configuration ADD training_round_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training_exercise_configuration ADD CONSTRAINT FK_D084BB6841654F37 FOREIGN KEY (training_round_id) REFERENCES training_round (id)');
        $this->addSql('CREATE INDEX IDX_D084BB6841654F37 ON training_exercise_configuration (training_round_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_exercise_configuration DROP FOREIGN KEY FK_D084BB6841654F37');
        $this->addSql('DROP INDEX IDX_D084BB6841654F37 ON training_exercise_configuration');
        $this->addSql('ALTER TABLE training_exercise_configuration DROP training_round_id');
    }
}
