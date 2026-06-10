<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260610090449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE test_result ADD CONSTRAINT FK_84B3C63D4A375ABF FOREIGN KEY (user_level_progress_id) REFERENCES user_level_progress (id)');
        $this->addSql('ALTER TABLE training_week_infos ADD CONSTRAINT FK_9FDFF188B8D45830 FOREIGN KEY (training_level_id) REFERENCES training_levels (id)');
        $this->addSql('ALTER TABLE user ADD training_goal VARCHAR(30) DEFAULT NULL, ADD estimated_level VARCHAR(30) DEFAULT NULL, ADD gender VARCHAR(20) DEFAULT NULL, ADD weight_kg DOUBLE PRECISION DEFAULT NULL, ADD height_cm INT DEFAULT NULL, ADD birth_date DATE DEFAULT NULL, ADD training_location VARCHAR(30) DEFAULT NULL, ADD xp INT NOT NULL DEFAULT 0, ADD streak_days INT NOT NULL DEFAULT 0, ADD total_workouts INT NOT NULL DEFAULT 0, ADD athlete_level INT NOT NULL DEFAULT 1, ADD last_workout_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_benchmark_attempt ADD CONSTRAINT FK_DC9B7129A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_benchmark_attempt ADD CONSTRAINT FK_DC9B7129E80D127B FOREIGN KEY (benchmark_id) REFERENCES benchmark (id)');
        $this->addSql('ALTER TABLE user_benchmark_exercise_log ADD CONSTRAINT FK_446161CCB191BE6B FOREIGN KEY (attempt_id) REFERENCES user_benchmark_attempt (id)');
        $this->addSql('ALTER TABLE user_benchmark_exercise_log ADD CONSTRAINT FK_446161CC4087AF9E FOREIGN KEY (benchmark_exercise_id) REFERENCES benchmark_exercise (id)');
        $this->addSql('ALTER TABLE user_level_progress ADD CONSTRAINT FK_C42D88CFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_level_progress ADD CONSTRAINT FK_C42D88CFB8D45830 FOREIGN KEY (training_level_id) REFERENCES training_levels (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE test_result DROP FOREIGN KEY FK_84B3C63D4A375ABF');
        $this->addSql('ALTER TABLE training_week_infos DROP FOREIGN KEY FK_9FDFF188B8D45830');
        $this->addSql('ALTER TABLE user DROP training_goal, DROP estimated_level, DROP gender, DROP weight_kg, DROP height_cm, DROP birth_date, DROP training_location, DROP xp, DROP streak_days, DROP total_workouts, DROP athlete_level, DROP last_workout_at');
        $this->addSql('ALTER TABLE user_benchmark_attempt DROP FOREIGN KEY FK_DC9B7129A76ED395');
        $this->addSql('ALTER TABLE user_benchmark_attempt DROP FOREIGN KEY FK_DC9B7129E80D127B');
        $this->addSql('ALTER TABLE user_benchmark_exercise_log DROP FOREIGN KEY FK_446161CCB191BE6B');
        $this->addSql('ALTER TABLE user_benchmark_exercise_log DROP FOREIGN KEY FK_446161CC4087AF9E');
        $this->addSql('ALTER TABLE user_level_progress DROP FOREIGN KEY FK_C42D88CFA76ED395');
        $this->addSql('ALTER TABLE user_level_progress DROP FOREIGN KEY FK_C42D88CFB8D45830');
    }
}
