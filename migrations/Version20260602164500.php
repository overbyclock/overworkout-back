<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Elimina campos de benchmark de la tabla training (migrados a tabla benchmark).
 */
final class Version20260602164500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Elimina columnas de benchmark de la tabla training tras migrar datos a tabla benchmark';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE training DROP COLUMN is_benchmark');
        $this->addSql('ALTER TABLE training DROP COLUMN benchmark_type');
        $this->addSql('ALTER TABLE training DROP COLUMN rx_weight_male');
        $this->addSql('ALTER TABLE training DROP COLUMN rx_weight_female');
        $this->addSql('ALTER TABLE training DROP COLUMN elite_time');
        $this->addSql('ALTER TABLE training DROP COLUMN advanced_time');
        $this->addSql('ALTER TABLE training DROP COLUMN intermediate_time');
        $this->addSql('ALTER TABLE training DROP COLUMN beginner_time');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE training ADD is_benchmark TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD benchmark_type VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD rx_weight_male VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD rx_weight_female VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD elite_time VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD advanced_time VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD intermediate_time VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD beginner_time VARCHAR(20) DEFAULT NULL');
    }
}
