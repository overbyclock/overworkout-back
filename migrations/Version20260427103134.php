<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260427103134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add estimated duration min/max columns to training table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE training ADD estimated_duration_min INT DEFAULT NULL, ADD estimated_duration_max INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE training DROP estimated_duration_min, DROP estimated_duration_max');
    }
}
