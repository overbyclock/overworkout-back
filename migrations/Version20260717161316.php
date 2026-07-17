<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260717161316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_training_session_step (id INT AUTO_INCREMENT NOT NULL, position INT NOT NULL, type VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, payload JSON NOT NULL, completed_at DATETIME DEFAULT NULL, log_id INT NOT NULL, INDEX IDX_5DDF9F65EA675D86 (log_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_training_session_step ADD CONSTRAINT FK_5DDF9F65EA675D86 FOREIGN KEY (log_id) REFERENCES user_training_log (id)');
        $this->addSql('ALTER TABLE user_training_log ADD current_position INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_training_session_step DROP FOREIGN KEY FK_5DDF9F65EA675D86');
        $this->addSql('DROP TABLE user_training_session_step');
        $this->addSql('ALTER TABLE user_training_log DROP current_position');
    }
}
