<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024203536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE training (id INT AUTO_INCREMENT NOT NULL, discipline VARCHAR(255) NOT NULL, target VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', rounds INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training_exercise_configuration (id INT AUTO_INCREMENT NOT NULL, exercise_id INT DEFAULT NULL, reps INT DEFAULT NULL, max_time_for_reps INT DEFAULT NULL, sets INT NOT NULL, rest_between_sets INT NOT NULL, weight INT DEFAULT NULL, INDEX IDX_D084BB68E934951A (exercise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training_round (id INT AUTO_INCREMENT NOT NULL, training_id INT DEFAULT NULL, round INT NOT NULL, rest_between_rounds INT NOT NULL, INDEX IDX_2A346059BEFD98D1 (training_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE training_exercise_configuration ADD CONSTRAINT FK_D084BB68E934951A FOREIGN KEY (exercise_id) REFERENCES exercises (id)');
        $this->addSql('ALTER TABLE training_round ADD CONSTRAINT FK_2A346059BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_exercise_configuration DROP FOREIGN KEY FK_D084BB68E934951A');
        $this->addSql('ALTER TABLE training_round DROP FOREIGN KEY FK_2A346059BEFD98D1');
        $this->addSql('DROP TABLE training');
        $this->addSql('DROP TABLE training_exercise_configuration');
        $this->addSql('DROP TABLE training_round');
    }
}
