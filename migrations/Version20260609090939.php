<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260609090939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE benchmark ADD format VARCHAR(30) DEFAULT NULL, ADD rounds INT DEFAULT NULL, ADD time_cap_seconds INT DEFAULT NULL, ADD rest_between_rounds_seconds INT DEFAULT NULL, ADD partitioning_allowed TINYINT NOT NULL, ADD timer_config JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE benchmark_exercise DROP FOREIGN KEY `FK_E4391895E80D127B`');
        $this->addSql('ALTER TABLE benchmark_exercise ADD CONSTRAINT FK_E4391895E80D127B FOREIGN KEY (benchmark_id) REFERENCES benchmark (id)');
        $this->addSql('ALTER TABLE test_result ADD CONSTRAINT FK_84B3C63D4A375ABF FOREIGN KEY (user_level_progress_id) REFERENCES user_level_progress (id)');
        $this->addSql('ALTER TABLE training_week_infos ADD CONSTRAINT FK_9FDFF188B8D45830 FOREIGN KEY (training_level_id) REFERENCES training_levels (id)');
        $this->addSql('ALTER TABLE user_level_progress ADD CONSTRAINT FK_C42D88CFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_level_progress ADD CONSTRAINT FK_C42D88CFB8D45830 FOREIGN KEY (training_level_id) REFERENCES training_levels (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE benchmark DROP format, DROP rounds, DROP time_cap_seconds, DROP rest_between_rounds_seconds, DROP partitioning_allowed, DROP timer_config');
        $this->addSql('ALTER TABLE benchmark_exercise DROP FOREIGN KEY FK_E4391895E80D127B');
        $this->addSql('ALTER TABLE benchmark_exercise ADD CONSTRAINT `FK_E4391895E80D127B` FOREIGN KEY (benchmark_id) REFERENCES benchmark (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE test_result DROP FOREIGN KEY FK_84B3C63D4A375ABF');
        $this->addSql('ALTER TABLE training_week_infos DROP FOREIGN KEY FK_9FDFF188B8D45830');
        $this->addSql('ALTER TABLE user_level_progress DROP FOREIGN KEY FK_C42D88CFA76ED395');
        $this->addSql('ALTER TABLE user_level_progress DROP FOREIGN KEY FK_C42D88CFB8D45830');
    }
}
