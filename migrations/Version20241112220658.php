<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112220658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_exercise_configuration CHANGE sets sets_for_exercise INT NOT NULL');
        $this->addSql('ALTER TABLE training_round CHANGE round sets_for_round INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_exercise_configuration CHANGE sets_for_exercise sets INT NOT NULL');
        $this->addSql('ALTER TABLE training_round CHANGE sets_for_round round INT NOT NULL');
    }
}
