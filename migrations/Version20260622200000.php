<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260622200000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea tablas de favoritos de entrenamientos y programas';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_favorite_training (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            training_id INT NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            UNIQUE INDEX unique_user_training_favorite (user_id, training_id),
            INDEX IDX_USER_FAVORITE_TRAINING_USER (user_id),
            INDEX IDX_USER_FAVORITE_TRAINING_TRAINING (training_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user_favorite_program (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            training_program_id INT NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            UNIQUE INDEX unique_user_program_favorite (user_id, training_program_id),
            INDEX IDX_USER_FAVORITE_PROGRAM_USER (user_id),
            INDEX IDX_USER_FAVORITE_PROGRAM_PROGRAM (training_program_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE user_favorite_training ADD CONSTRAINT FK_UFT_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_training ADD CONSTRAINT FK_UFT_TRAINING FOREIGN KEY (training_id) REFERENCES training (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_program ADD CONSTRAINT FK_UFP_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_program ADD CONSTRAINT FK_UFP_PROGRAM FOREIGN KEY (training_program_id) REFERENCES training_programs (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_favorite_program');
        $this->addSql('DROP TABLE user_favorite_training');
    }
}
