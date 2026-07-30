<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260725122007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mobility_gate (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, criteria LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, program_id INT DEFAULT NULL, INDEX IDX_5BF2711B3EB8070A (program_id), UNIQUE INDEX uniq_mobility_gate_program_code (program_id, code), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_achievement (id INT AUTO_INCREMENT NOT NULL, unlocked_at DATETIME NOT NULL, context JSON DEFAULT NULL, user_id INT NOT NULL, achievement_id INT NOT NULL, INDEX IDX_3F68B664A76ED395 (user_id), INDEX IDX_3F68B664B3EC99FE (achievement_id), UNIQUE INDEX uniq_user_achievement (user_id, achievement_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mobility_gate ADD CONSTRAINT FK_5BF2711B3EB8070A FOREIGN KEY (program_id) REFERENCES training_programs (id)');
        $this->addSql('ALTER TABLE user_achievement ADD CONSTRAINT FK_3F68B664A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_achievement ADD CONSTRAINT FK_3F68B664B3EC99FE FOREIGN KEY (achievement_id) REFERENCES achievements (id)');
        $this->addSql('ALTER TABLE achievements ADD tier VARCHAR(20) DEFAULT NULL, ADD type VARCHAR(20) DEFAULT NULL, ADD special_celebration TINYINT DEFAULT 0 NOT NULL, ADD level_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE achievements ADD CONSTRAINT FK_D1227EFE5FB14BA7 FOREIGN KEY (level_id) REFERENCES training_levels (id)');
        $this->addSql('CREATE INDEX IDX_D1227EFE5FB14BA7 ON achievements (level_id)');
        $this->addSql('ALTER TABLE test_result ADD strength_passed TINYINT DEFAULT 1 NOT NULL, ADD mobility_passed TINYINT DEFAULT 1 NOT NULL, ADD failed_gates JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE training_programs ADD test_evaluation_mode VARCHAR(20) DEFAULT \'legacy\' NOT NULL');
        $this->addSql('ALTER TABLE training_round ADD block_type VARCHAR(30) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mobility_gate DROP FOREIGN KEY FK_5BF2711B3EB8070A');
        $this->addSql('ALTER TABLE user_achievement DROP FOREIGN KEY FK_3F68B664A76ED395');
        $this->addSql('ALTER TABLE user_achievement DROP FOREIGN KEY FK_3F68B664B3EC99FE');
        $this->addSql('DROP TABLE mobility_gate');
        $this->addSql('DROP TABLE user_achievement');
        $this->addSql('ALTER TABLE achievements DROP FOREIGN KEY FK_D1227EFE5FB14BA7');
        $this->addSql('DROP INDEX IDX_D1227EFE5FB14BA7 ON achievements');
        $this->addSql('ALTER TABLE achievements DROP tier, DROP type, DROP special_celebration, DROP level_id');
        $this->addSql('ALTER TABLE test_result DROP strength_passed, DROP mobility_passed, DROP failed_gates');
        $this->addSql('ALTER TABLE training_programs DROP test_evaluation_mode');
        $this->addSql('ALTER TABLE training_round DROP block_type');
    }
}
