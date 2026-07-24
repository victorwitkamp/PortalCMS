<?php

declare(strict_types=1);

namespace PortalCMS\Core\Database\Migrations;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\Migrations\AbstractMigration;

/**
 * Base class for the guarded schema baseline.
 * Every operation checks the live schema before acting, so the exact same
 * baseline is safe to run against a fresh table, a partially upgraded legacy
 * table, or a table that is already fully current: it becomes a
 * no-op wherever the target state already exists.
 */
abstract class AbstractGuardedMigration extends AbstractMigration
{
    protected function schemaManager(): AbstractSchemaManager
    {
        return $this->connection->createSchemaManager();
    }

    protected function tableExists(string $tableName): bool
    {
        return $this->schemaManager()->tablesExist([ $tableName ]);
    }

    protected function ensureTable(string $tableName, string $createTableSql): void
    {
        if (!$this->tableExists($tableName)) {
            $this->addSql($createTableSql);
        }
    }

    protected function columnExists(string $tableName, string $columnName): bool
    {
        return $this->tableExists($tableName) && $this->schemaManager()->introspectTable($tableName)->hasColumn($columnName);
    }

    protected function ensureColumn(string $tableName, string $columnName, string $addColumnSql): void
    {
        if ($this->tableExists($tableName) && !$this->schemaManager()->introspectTable($tableName)->hasColumn($columnName)) {
            $this->addSql($addColumnSql);
        }
    }

    protected function ensureIndex(string $tableName, string $indexName, string $addIndexSql): void
    {
        if ($this->tableExists($tableName) && !$this->schemaManager()->introspectTable($tableName)->hasIndex($indexName)) {
            $this->addSql($addIndexSql);
        }
    }

    protected function ensureForeignKey(string $tableName, string $constraintName, string $addForeignKeySql): void
    {
        if ($this->tableExists($tableName) && !$this->schemaManager()->introspectTable($tableName)->hasForeignKey($constraintName)) {
            $this->addSql($addForeignKeySql);
        }
    }

    /**
     * Renames a column found under an old name to the current name, but only
     * if the old name is present and the new one is not yet present. For
     * example, `users.user_password_reset_hash` in legacy backups became
     * `password_reset_hash`. Schema diffing alone can't distinguish "renamed"
     * from "dropped one, added an unrelated other," so these are only added
     * where a real historical dump proved the rename actually happened.
     */
    protected function renameColumnIfNeeded(string $tableName, string $oldName, string $newName, string $renameSql): void
    {
        if (!$this->tableExists($tableName)) {
            return;
        }
        $table = $this->schemaManager()->introspectTable($tableName);
        if ($table->hasColumn($oldName) && !$table->hasColumn($newName)) {
            $this->addSql($renameSql);
        }
    }

    // No down() override: reconciling an unknown schema with the current
    // baseline has no meaningful undo. The parent aborts with a clear
    // "not implemented" message.
}
