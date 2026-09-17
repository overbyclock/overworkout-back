<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Añade status (in_progress|completed|abandoned) a user_training_log:
 * - completed_at NOT NULL => completed
 * - sesiones abiertas de programas abandonados por el usuario => abandoned
 * - el resto => in_progress (default de la columna)
 */
final class Version20260902150534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Añade status a user_training_log y migra los datos existentes';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_training_log ADD status VARCHAR(20) DEFAULT \'in_progress\' NOT NULL');
        $this->addSql('UPDATE user_training_log SET status = \'completed\' WHERE completed_at IS NOT NULL');
        // Sesiones abiertas cuyo nivel pertenece a un programa que el usuario ha abandonado.
        $this->addSql(
            'UPDATE user_training_log log
             INNER JOIN training t ON log.training_id = t.id
             INNER JOIN user_level_progress ulp ON ulp.training_level_id = t.training_level_id AND ulp.user_id = log.user_id
             SET log.status = \'abandoned\'
             WHERE log.completed_at IS NULL AND ulp.status = \'abandoned\''
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_training_log DROP status');
    }
}
