<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260726000004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Preserve longer legacy activity and email data and remove the obsolete role type.';
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

        $this->addSql('ALTER TABLE activity CHANGE details details TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE contracts CHANGE bandleider_email bandleider_email VARCHAR(254) DEFAULT NULL');

        $roles = $this->connection->createSchemaManager()->introspectTable('roles');
        if (!$roles->hasColumn('type')) {
            return;
        }

        $unexpectedRoleTypes = (int) $this->connection->fetchOne(
            "SELECT COUNT(*) FROM roles WHERE type IS NULL OR type <> 'CUSTOM'",
        );
        $this->abortIf(
            $unexpectedRoleTypes > 0,
            'roles.type contains values other than the known legacy CUSTOM value; review them before migrating.',
        );

        $this->addSql('ALTER TABLE roles DROP type');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException(
            'Restoring the legacy column lengths could truncate data, and roles.type carried no application state.',
        );
    }
}
