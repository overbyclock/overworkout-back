<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260609120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Añade campos de instrucciones y estándares a exercises';
    }

    public function up(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform();

        if ($platform instanceof SQLitePlatform) {
            $this->addSql('ALTER TABLE exercises ADD COLUMN setup_instructions TEXT DEFAULT NULL');
            $this->addSql('ALTER TABLE exercises ADD COLUMN execution_instructions TEXT DEFAULT NULL');
            $this->addSql('ALTER TABLE exercises ADD COLUMN movement_standards TEXT DEFAULT NULL');
            $this->addSql('ALTER TABLE exercises ADD COLUMN common_faults CLOB DEFAULT NULL');
            $this->addSql('ALTER TABLE exercises ADD COLUMN safety_tips TEXT DEFAULT NULL');

            return;
        }

        $this->addSql('ALTER TABLE exercises ADD setup_instructions LONGTEXT DEFAULT NULL, ADD execution_instructions LONGTEXT DEFAULT NULL, ADD movement_standards LONGTEXT DEFAULT NULL, ADD common_faults JSON DEFAULT NULL, ADD safety_tips LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform();

        if ($platform instanceof SQLitePlatform) {
            // SQLite no soporta DROP COLUMN de forma nativa; requiere recrear tabla
            $this->addSql('-- SQLite no soporta DROP COLUMN directamente');

            return;
        }

        $this->addSql('ALTER TABLE exercises DROP setup_instructions, DROP execution_instructions, DROP movement_standards, DROP common_faults, DROP safety_tips');
    }
}
