<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260702171621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Añade started_at y completa completed_at en user_training_log';
    }

    public function up(Schema $schema): void
    {
        // Añadir la columna de inicio de sesión y permitir valores nulos en la de fin.
        $this->addSql('ALTER TABLE user_training_log ADD started_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE user_training_log CHANGE completed_at completed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');

        // Para registros históricos, utilizar el momento de completado como inicio aproximado.
        $this->addSql('UPDATE user_training_log SET started_at = completed_at WHERE started_at IS NULL');
    }

    public function down(Schema $schema): void
    {
        // Revertir los cambios de esquema.
        $this->addSql('ALTER TABLE user_training_log CHANGE completed_at completed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE user_training_log DROP started_at');
    }
}
