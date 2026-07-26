<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260726000003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align integer semantics, defaults, and relation index names with the ORM mapping.';
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof AbstractMySQLPlatform,
            'This migration can only be executed safely on MySQL.',
        );

        $this->addSql('ALTER TABLE mail_batches CHANGE status status SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE role_perm RENAME INDEX role_id TO IDX_94D2024ED60322AC');
        $this->addSql('ALTER TABLE role_perm RENAME INDEX perm_id TO IDX_94D2024EFA6311EF');
        $this->addSql('ALTER TABLE users CHANGE user_account_type user_account_type SMALLINT DEFAULT 1 NOT NULL, CHANGE user_failed_logins user_failed_logins SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE user_role CHANGE role_id role_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_role RENAME INDEX user_id TO IDX_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_role RENAME INDEX role_id TO IDX_2DE8C6A3D60322AC');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof AbstractMySQLPlatform,
            'This migration can only be executed safely on MySQL.',
        );

        $this->addSql('ALTER TABLE mail_batches CHANGE status status TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE role_perm RENAME INDEX IDX_94D2024ED60322AC TO role_id');
        $this->addSql('ALTER TABLE role_perm RENAME INDEX IDX_94D2024EFA6311EF TO perm_id');
        $this->addSql('ALTER TABLE users CHANGE user_account_type user_account_type TINYINT(1) DEFAULT 1 NOT NULL, CHANGE user_failed_logins user_failed_logins TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE user_role CHANGE role_id role_id INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE user_role RENAME INDEX IDX_2DE8C6A3A76ED395 TO user_id');
        $this->addSql('ALTER TABLE user_role RENAME INDEX IDX_2DE8C6A3D60322AC TO role_id');
    }
}
