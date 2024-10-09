<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008205424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipments (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exercises ADD equipment_id INT DEFAULT NULL, DROP require_equipment');
        $this->addSql('ALTER TABLE exercises ADD CONSTRAINT FK_FA14991517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipments (id)');
        $this->addSql('CREATE INDEX IDX_FA14991517FE9FE ON exercises (equipment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercises DROP FOREIGN KEY FK_FA14991517FE9FE');
        $this->addSql('DROP TABLE equipments');
        $this->addSql('DROP INDEX IDX_FA14991517FE9FE ON exercises');
        $this->addSql('ALTER TABLE exercises ADD require_equipment TINYINT(1) NOT NULL, DROP equipment_id');
    }
}
