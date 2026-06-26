<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260626110643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_training_log (id INT AUTO_INCREMENT NOT NULL, source VARCHAR(30) NOT NULL, completed_at DATETIME NOT NULL, duration_seconds INT DEFAULT NULL, xp_earned INT NOT NULL, user_id INT NOT NULL, training_id INT DEFAULT NULL, INDEX IDX_954D638EA76ED395 (user_id), INDEX IDX_954D638EBEFD98D1 (training_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_training_log ADD CONSTRAINT FK_954D638EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_training_log ADD CONSTRAINT FK_954D638EBEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_training_log DROP FOREIGN KEY FK_954D638EA76ED395');
        $this->addSql('ALTER TABLE user_training_log DROP FOREIGN KEY FK_954D638EBEFD98D1');
        $this->addSql('DROP TABLE user_training_log');
    }
}
