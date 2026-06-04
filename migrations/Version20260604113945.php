<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260604113945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE benchmark_exercise (id INT AUTO_INCREMENT NOT NULL, position INT NOT NULL, reps INT DEFAULT NULL, distance_meters INT DEFAULT NULL, duration_seconds INT DEFAULT NULL, calories INT DEFAULT NULL, rx_weight_male VARCHAR(50) DEFAULT NULL, rx_weight_female VARCHAR(50) DEFAULT NULL, rest_seconds INT DEFAULT NULL, benchmark_id INT NOT NULL, exercise_id INT NOT NULL, INDEX IDX_E4391895E80D127B (benchmark_id), INDEX IDX_E4391895E934951A (exercise_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE benchmark_exercise ADD CONSTRAINT FK_E4391895E80D127B FOREIGN KEY (benchmark_id) REFERENCES benchmark (id)');
        $this->addSql('ALTER TABLE benchmark_exercise ADD CONSTRAINT FK_E4391895E934951A FOREIGN KEY (exercise_id) REFERENCES exercises (id)');
        $this->addSql('ALTER TABLE test_result ADD CONSTRAINT FK_84B3C63D4A375ABF FOREIGN KEY (user_level_progress_id) REFERENCES user_level_progress (id)');
        $this->addSql('ALTER TABLE training_week_infos ADD CONSTRAINT FK_9FDFF188B8D45830 FOREIGN KEY (training_level_id) REFERENCES training_levels (id)');
        $this->addSql('ALTER TABLE user_level_progress ADD CONSTRAINT FK_C42D88CFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_level_progress ADD CONSTRAINT FK_C42D88CFB8D45830 FOREIGN KEY (training_level_id) REFERENCES training_levels (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE benchmark_exercise DROP FOREIGN KEY FK_E4391895E80D127B');
        $this->addSql('ALTER TABLE benchmark_exercise DROP FOREIGN KEY FK_E4391895E934951A');
        $this->addSql('DROP TABLE benchmark_exercise');
        $this->addSql('ALTER TABLE test_result DROP FOREIGN KEY FK_84B3C63D4A375ABF');
        $this->addSql('ALTER TABLE training_week_infos DROP FOREIGN KEY FK_9FDFF188B8D45830');
        $this->addSql('ALTER TABLE user_level_progress DROP FOREIGN KEY FK_C42D88CFA76ED395');
        $this->addSql('ALTER TABLE user_level_progress DROP FOREIGN KEY FK_C42D88CFB8D45830');
    }
}
