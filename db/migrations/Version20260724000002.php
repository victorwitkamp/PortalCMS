<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use PortalCMS\Core\Database\Migrations\AbstractGuardedMigration;

/**
 * Squashed PortalCMS baseline.
 *
 * This migration creates the latest schema on an empty database and reconciles
 * known pre-Doctrine schemas in place. It is intentionally forward-only.
 */
final class Version20260724000002 extends AbstractGuardedMigration
{
    /**
     * @var array<string, array{
     *     columns: array<string, string>,
     *     primary: list<string>,
     *     indexes?: array<string, array{columns: list<string>, unique?: bool}>,
     *     foreign_keys?: array<string, array{
     *         columns: list<string>,
     *         foreign_table: string,
     *         foreign_columns: list<string>
     *     }>
     * }>
     */
    private const TABLES = [
        'contracts' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'bandcode' => 'varchar(2) DEFAULT NULL',
                'beuk_vertegenwoordiger' => 'varchar(50) DEFAULT NULL',
                'band_naam' => 'varchar(50) NOT NULL',
                'bandleider_naam' => 'varchar(50) DEFAULT NULL',
                'bandleider_adres' => 'varchar(50) DEFAULT NULL',
                'bandleider_postcode' => 'varchar(6) DEFAULT NULL',
                'bandleider_woonplaats' => 'varchar(30) DEFAULT NULL',
                'bandleider_geboortedatum' => 'varchar(10) DEFAULT NULL',
                'bandleider_telefoonnummer1' => 'varchar(10) DEFAULT NULL',
                'bandleider_telefoonnummer2' => 'varchar(10) DEFAULT NULL',
                'bandleider_email' => 'varchar(30) DEFAULT NULL',
                'bandleider_bsn' => 'varchar(9) DEFAULT NULL',
                'huur_oefenruimte_nr' => 'varchar(1) DEFAULT NULL',
                'huur_dag' => 'varchar(9) DEFAULT NULL',
                'huur_start' => 'time DEFAULT NULL',
                'huur_einde' => 'time DEFAULT NULL',
                'huur_kast_nr' => 'varchar(1) DEFAULT NULL',
                'kosten_ruimte' => 'decimal(10,2) DEFAULT NULL',
                'kosten_kast' => 'decimal(10,2) DEFAULT NULL',
                'kosten_totaal' => 'decimal(10,2) DEFAULT NULL',
                'kosten_borg' => 'decimal(10,2) DEFAULT NULL',
                'contract_ingangsdatum' => 'date DEFAULT NULL',
                'contract_einddatum' => 'date DEFAULT NULL',
                'contract_datum' => 'date DEFAULT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
        ],
        'roles' => [
            'columns' => [
                'role_id' => 'int NOT NULL AUTO_INCREMENT',
                'role_name' => 'varchar(50) NOT NULL',
            ],
            'primary' => [ 'role_id' ],
        ],
        'permissions' => [
            'columns' => [
                'perm_id' => 'int NOT NULL AUTO_INCREMENT',
                'perm_desc' => 'varchar(50) NOT NULL',
            ],
            'primary' => [ 'perm_id' ],
        ],
        'users' => [
            'columns' => [
                'user_id' => 'int NOT NULL AUTO_INCREMENT',
                'user_name' => 'varchar(64) NOT NULL',
                'session_id' => 'varchar(48) DEFAULT NULL',
                'user_password_hash' => 'varchar(255) DEFAULT NULL',
                'user_email' => 'varchar(254) NOT NULL',
                'user_active' => 'tinyint(1) NOT NULL DEFAULT 0',
                'user_deleted' => 'tinyint(1) NOT NULL DEFAULT 0',
                'user_account_type' => 'tinyint(1) NOT NULL DEFAULT 1',
                'user_has_avatar' => 'tinyint(1) NOT NULL DEFAULT 0',
                'user_remember_me_token' => 'varchar(64) DEFAULT NULL',
                'user_suspension_timestamp' => 'bigint DEFAULT NULL',
                'user_last_login_timestamp' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                'user_failed_logins' => 'tinyint(1) NOT NULL DEFAULT 0',
                'user_last_failed_login' => 'timestamp NULL DEFAULT NULL',
                'user_activation_hash' => 'varchar(40) DEFAULT NULL',
                'password_reset_hash' => 'char(40) DEFAULT NULL',
                'user_password_reset_timestamp' => 'timestamp NULL DEFAULT NULL',
                'user_provider_type' => 'text',
                'user_fbid' => 'varchar(100) DEFAULT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'user_id' ],
            'indexes' => [
                'user_name' => [
                    'columns' => [ 'user_name' ],
                    'unique' => true,
                ],
                'user_email' => [
                    'columns' => [ 'user_email' ],
                    'unique' => true,
                ],
            ],
        ],
        'activity' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'user_id' => 'int DEFAULT NULL',
                'user_name' => 'varchar(64) DEFAULT NULL',
                'ip_address' => 'varchar(45) DEFAULT NULL',
                'activity' => 'varchar(32) NOT NULL',
                'details' => 'varchar(32) DEFAULT NULL',
            ],
            'primary' => [ 'id' ],
            'indexes' => [
                'IDX_AC74095AA76ED395' => [
                    'columns' => [ 'user_id' ],
                ],
            ],
            'foreign_keys' => [
                'FK_AC74095AA76ED395' => [
                    'columns' => [ 'user_id' ],
                    'foreign_table' => 'users',
                    'foreign_columns' => [ 'user_id' ],
                ],
            ],
        ],
        'events' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'CreatedBy' => 'int NOT NULL',
                'title' => 'varchar(255) NOT NULL',
                'start_event' => 'datetime NOT NULL',
                'end_event' => 'datetime NOT NULL',
                'description' => 'text',
                'status' => 'int DEFAULT 0',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
        ],
        'mail_batches' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'status' => 'tinyint(1) NOT NULL DEFAULT 0',
                'DateSent' => 'timestamp NULL DEFAULT NULL',
                'UsedTemplate' => 'int DEFAULT NULL',
                'CreatedBy' => 'int DEFAULT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
        ],
        'mail_schedule' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'batch_id' => 'int DEFAULT NULL',
                'sender_email' => 'varchar(254) DEFAULT NULL',
                'recipient_email' => 'varchar(254) DEFAULT NULL',
                'subject' => 'varchar(255) DEFAULT NULL',
                'body' => 'text',
                'attachment' => 'text',
                'member_id' => 'int DEFAULT NULL',
                'user_id' => 'int DEFAULT NULL',
                'status' => 'int NOT NULL DEFAULT 1',
                'errormessage' => 'text',
                'DateSent' => 'timestamp NULL DEFAULT NULL',
                'CreatedBy' => 'int DEFAULT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
        ],
        'mail_recipients' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'name' => 'varchar(64) DEFAULT NULL',
                'email' => 'varchar(254) NOT NULL',
                'type' => 'int NOT NULL DEFAULT 1',
                'mail_id' => 'int NOT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
            'indexes' => [
                'IDX_C0A5D838C8776F01' => [
                    'columns' => [ 'mail_id' ],
                ],
            ],
            'foreign_keys' => [
                'FK_C0A5D838C8776F01' => [
                    'columns' => [ 'mail_id' ],
                    'foreign_table' => 'mail_schedule',
                    'foreign_columns' => [ 'id' ],
                ],
            ],
        ],
        'mail_templates' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'type' => 'varchar(32) DEFAULT NULL',
                'name' => 'varchar(32) DEFAULT NULL',
                'subject' => 'varchar(255) DEFAULT NULL',
                'body' => 'text',
                'status' => 'int NOT NULL DEFAULT 1',
                'CreatedBy' => 'int DEFAULT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
        ],
        'members' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'jaarlidmaatschap' => 'int DEFAULT NULL',
                'voorletters' => 'varchar(30) DEFAULT NULL',
                'voornaam' => 'varchar(30) DEFAULT NULL',
                'achternaam' => 'varchar(30) DEFAULT NULL',
                'geboortedatum' => 'varchar(10) DEFAULT NULL',
                'adres' => 'varchar(50) DEFAULT NULL',
                'postcode' => 'varchar(6) DEFAULT NULL',
                'huisnummer' => 'varchar(6) DEFAULT NULL',
                'woonplaats' => 'varchar(30) DEFAULT NULL',
                'telefoon_vast' => 'varchar(10) DEFAULT NULL',
                'telefoon_mobiel' => 'varchar(10) DEFAULT NULL',
                'emailadres' => 'varchar(254) DEFAULT NULL',
                'ingangsdatum' => 'varchar(10) DEFAULT NULL',
                'geslacht' => 'varchar(50) DEFAULT NULL',
                'nieuwsbrief' => 'tinyint DEFAULT NULL',
                'vrijwilliger' => 'tinyint DEFAULT NULL',
                'vrijwilligeroptie1' => 'tinyint DEFAULT NULL',
                'vrijwilligeroptie2' => 'tinyint DEFAULT NULL',
                'vrijwilligeroptie3' => 'tinyint DEFAULT NULL',
                'vrijwilligeroptie4' => 'tinyint DEFAULT NULL',
                'vrijwilligeroptie5' => 'tinyint DEFAULT NULL',
                'betalingswijze' => 'varchar(30) DEFAULT NULL',
                'iban' => 'varchar(30) DEFAULT NULL',
                'machtigingskenmerk' => 'varchar(30) DEFAULT NULL',
                'status' => 'int DEFAULT 0',
                'opmerking' => 'varchar(30) DEFAULT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
        ],
        'pages' => [
            'columns' => [
                'id' => 'varchar(32) NOT NULL',
                'name' => 'varchar(32) DEFAULT NULL',
                'content' => 'text',
                'ModificationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
        ],
        'products' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'name' => 'varchar(50) NOT NULL',
                'type' => 'int NOT NULL DEFAULT 1',
                'price' => 'int NOT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
        ],
        'site_settings' => [
            'columns' => [
                'setting' => 'varchar(32) NOT NULL',
                'string_value' => 'varchar(64) DEFAULT NULL',
                'ModificationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'setting' ],
        ],
        'mail_attachments' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'mail_id' => 'int DEFAULT NULL',
                'template_id' => 'int DEFAULT NULL',
                'path' => 'varchar(254) DEFAULT NULL',
                'name' => 'varchar(255) DEFAULT NULL',
                'extension' => 'varchar(255) DEFAULT NULL',
                'encoding' => 'varchar(255) DEFAULT NULL',
                'type' => 'varchar(255) DEFAULT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
            'indexes' => [
                'IDX_F31B870BC8776F01' => [
                    'columns' => [ 'mail_id' ],
                ],
                'IDX_F31B870B5DA0FB8' => [
                    'columns' => [ 'template_id' ],
                ],
            ],
            'foreign_keys' => [
                'FK_F31B870BC8776F01' => [
                    'columns' => [ 'mail_id' ],
                    'foreign_table' => 'mail_schedule',
                    'foreign_columns' => [ 'id' ],
                ],
                'FK_F31B870B5DA0FB8' => [
                    'columns' => [ 'template_id' ],
                    'foreign_table' => 'mail_templates',
                    'foreign_columns' => [ 'id' ],
                ],
            ],
        ],
        'invoices' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'contract_id' => 'int DEFAULT NULL',
                'year' => 'int DEFAULT NULL',
                'month' => 'int DEFAULT NULL',
                'factuurnummer' => 'varchar(8) DEFAULT NULL',
                'factuurdatum' => 'date NOT NULL',
                'vervaldatum' => 'date DEFAULT NULL',
                'status' => 'int DEFAULT 0',
                'mail_id' => 'int DEFAULT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
            'indexes' => [
                'contract_id' => [
                    'columns' => [ 'contract_id' ],
                ],
            ],
            'foreign_keys' => [
                'invoices_ibfk_1' => [
                    'columns' => [ 'contract_id' ],
                    'foreign_table' => 'contracts',
                    'foreign_columns' => [ 'id' ],
                ],
            ],
        ],
        'invoice_items' => [
            'columns' => [
                'id' => 'int NOT NULL AUTO_INCREMENT',
                'invoice_id' => 'int NOT NULL',
                'name' => 'varchar(50) NOT NULL',
                'price' => 'int NOT NULL',
                'CreationDate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'ModificationDate' => 'timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
            ],
            'primary' => [ 'id' ],
            'indexes' => [
                'invoice_id' => [
                    'columns' => [ 'invoice_id' ],
                ],
            ],
            'foreign_keys' => [
                'invoice_items_ibfk_1' => [
                    'columns' => [ 'invoice_id' ],
                    'foreign_table' => 'invoices',
                    'foreign_columns' => [ 'id' ],
                ],
            ],
        ],
        'role_perm' => [
            'columns' => [
                'role_id' => 'int NOT NULL',
                'perm_id' => 'int NOT NULL',
            ],
            'primary' => [ 'role_id', 'perm_id' ],
            'indexes' => [
                'role_id' => [
                    'columns' => [ 'role_id' ],
                ],
                'perm_id' => [
                    'columns' => [ 'perm_id' ],
                ],
            ],
            'foreign_keys' => [
                'role_perm_ibfk_1' => [
                    'columns' => [ 'role_id' ],
                    'foreign_table' => 'roles',
                    'foreign_columns' => [ 'role_id' ],
                ],
                'role_perm_ibfk_2' => [
                    'columns' => [ 'perm_id' ],
                    'foreign_table' => 'permissions',
                    'foreign_columns' => [ 'perm_id' ],
                ],
            ],
        ],
        'user_role' => [
            'columns' => [
                'user_id' => 'int NOT NULL',
                'role_id' => 'int NOT NULL DEFAULT 1',
            ],
            'primary' => [ 'user_id', 'role_id' ],
            'indexes' => [
                'user_id' => [
                    'columns' => [ 'user_id' ],
                ],
                'role_id' => [
                    'columns' => [ 'role_id' ],
                ],
            ],
            'foreign_keys' => [
                'user_role_ibfk_1' => [
                    'columns' => [ 'user_id' ],
                    'foreign_table' => 'users',
                    'foreign_columns' => [ 'user_id' ],
                ],
                'user_role_ibfk_2' => [
                    'columns' => [ 'role_id' ],
                    'foreign_table' => 'roles',
                    'foreign_columns' => [ 'role_id' ],
                ],
            ],
        ],
    ];

    private const CONTRACT_DATE_COLUMNS = [
        'contract_ingangsdatum',
        'contract_einddatum',
        'contract_datum',
    ];

    private const CONTRACT_TIME_COLUMNS = [
        'huur_start',
        'huur_einde',
    ];

    private const CONTRACT_MONEY_COLUMNS = [
        'kosten_ruimte',
        'kosten_kast',
        'kosten_totaal',
        'kosten_borg',
    ];

    public function getDescription(): string
    {
        return 'Create or reconcile the complete PortalCMS schema at the squashed Doctrine baseline.';
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->tableExists('band_contracts') && !$this->tableExists('contracts'),
            'Legacy table band_contracts must be renamed by db/import.php before migration.',
        );

        $this->validatePrimaryKeys();
        $this->normalizeContracts();
        $this->normalizeUsers();
        $this->cleanOrphanedReferences();
        $this->normalizeKnownColumns();

        foreach (self::TABLES as $tableName => $definition) {
            $this->reconcileTable($tableName, $definition);
        }

        // Existing installations may contain any subset of the 24 migration
        // versions replaced by this baseline. Keep only the squashed version;
        // Doctrine records this class immediately after the migration ends.
        $this->addSql(
            'DELETE FROM doctrine_migration_versions WHERE version <> ?',
            [ self::class ],
        );
    }

    private function validatePrimaryKeys(): void
    {
        foreach (self::TABLES as $tableName => $definition) {
            if (!$this->tableExists($tableName)) {
                continue;
            }

            $table = $this->schemaManager()->introspectTable($tableName);
            if ($table->getPrimaryKey() !== null) {
                continue;
            }

            foreach ($definition['primary'] as $columnName) {
                if (!$table->hasColumn($columnName)) {
                    continue 2;
                }
            }

            $columns = implode(', ', array_map(
                static fn (string $columnName): string => sprintf('`%s`', $columnName),
                $definition['primary'],
            ));
            $nullPredicate = implode(' OR ', array_map(
                static fn (string $columnName): string => sprintf('`%s` IS NULL', $columnName),
                $definition['primary'],
            ));

            $this->abortIf(
                (int) $this->connection->fetchOne(
                    sprintf('SELECT COUNT(*) FROM `%s` WHERE %s', $tableName, $nullPredicate),
                ) > 0,
                sprintf('%s contains null values in its future primary key.', $tableName),
            );
            $this->abortIf(
                (int) $this->connection->fetchOne(
                    sprintf(
                        'SELECT COUNT(*) FROM ('
                        . 'SELECT %2$s FROM `%1$s` GROUP BY %2$s HAVING COUNT(*) > 1'
                        . ') duplicate_rows',
                        $tableName,
                        $columns,
                    ),
                ) > 0,
                sprintf('%s contains duplicate values in its future primary key.', $tableName),
            );
        }

        if ($this->tableExists('pages') && $this->columnExists('pages', 'id')) {
            $this->abortIf(
                (int) $this->connection->fetchOne(
                    'SELECT COUNT(*) FROM pages WHERE id IS NULL OR id = \'\''
                ) > 0,
                'pages contains empty keys.',
            );
        }
        if ($this->tableExists('site_settings') && $this->columnExists('site_settings', 'setting')) {
            $this->abortIf(
                (int) $this->connection->fetchOne(
                    'SELECT COUNT(*) FROM site_settings WHERE setting IS NULL OR setting = \'\''
                ) > 0,
                'site_settings contains empty keys.',
            );
        }
    }

    private function normalizeContracts(): void
    {
        if (!$this->tableExists('contracts')) {
            return;
        }

        foreach (self::CONTRACT_DATE_COLUMNS as $columnName) {
            if (!$this->columnExists('contracts', $columnName) || $this->columnType('contracts', $columnName) === 'date') {
                continue;
            }

            $this->abortIfUnexpectedContractValues(
                $columnName,
                '^(?:[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}|[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}|[0-9]{1,2}/[0-9]{1,2}/[0-9]{4})$',
                'date',
            );
            $this->addSql(sprintf(
                "UPDATE contracts SET `%1\$s` = NULL "
                . "WHERE `%1\$s` IS NOT NULL AND TRIM(`%1\$s`) IN ('', '0000-00-00', '00-00-0000', '00/00/0000')",
                $columnName,
            ));
            $this->addSql(sprintf(
                "UPDATE contracts SET `%1\$s` = DATE_FORMAT(STR_TO_DATE(TRIM(`%1\$s`), '%%e-%%c-%%Y'), '%%Y-%%m-%%d') "
                . "WHERE TRIM(`%1\$s`) REGEXP '^[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}$'",
                $columnName,
            ));
            $this->addSql(sprintf(
                "UPDATE contracts SET `%1\$s` = DATE_FORMAT(STR_TO_DATE(TRIM(`%1\$s`), '%%e/%%c/%%Y'), '%%Y-%%m-%%d') "
                . "WHERE TRIM(`%1\$s`) REGEXP '^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$'",
                $columnName,
            ));
            $this->addSql(sprintf(
                'UPDATE contracts SET `%1$s` = TRIM(`%1$s`) WHERE `%1$s` IS NOT NULL',
                $columnName,
            ));
            $this->addSql(sprintf(
                'ALTER TABLE contracts MODIFY `%s` date DEFAULT NULL',
                $columnName,
            ));
        }

        foreach (self::CONTRACT_TIME_COLUMNS as $columnName) {
            if (!$this->columnExists('contracts', $columnName) || $this->columnType('contracts', $columnName) === 'time') {
                continue;
            }

            $this->abortIfUnexpectedContractValues(
                $columnName,
                '^[0-9]{1,2}:[0-9]{2}(?::[0-9]{2})?$',
                'time',
            );
            $this->addSql(sprintf(
                "UPDATE contracts SET `%1\$s` = NULL WHERE `%1\$s` IS NOT NULL AND TRIM(`%1\$s`) = ''",
                $columnName,
            ));
            $this->addSql(sprintf(
                'UPDATE contracts SET `%1$s` = TRIM(`%1$s`) WHERE `%1$s` IS NOT NULL',
                $columnName,
            ));
            $this->addSql(sprintf(
                'ALTER TABLE contracts MODIFY `%s` time DEFAULT NULL',
                $columnName,
            ));
        }

        foreach (self::CONTRACT_MONEY_COLUMNS as $columnName) {
            if (!$this->columnExists('contracts', $columnName) || $this->columnType('contracts', $columnName) === 'decimal') {
                continue;
            }

            $this->abortIfUnexpectedContractValues(
                $columnName,
                '^-?[0-9]+(?:[.,][0-9]{1,2})?$',
                'money',
            );
            $this->addSql(sprintf(
                "UPDATE contracts SET `%1\$s` = NULL WHERE `%1\$s` IS NOT NULL AND TRIM(`%1\$s`) = ''",
                $columnName,
            ));
            $this->addSql(sprintf(
                "UPDATE contracts SET `%1\$s` = REPLACE(TRIM(`%1\$s`), ',', '.') WHERE `%1\$s` IS NOT NULL",
                $columnName,
            ));
            $this->addSql(sprintf(
                'ALTER TABLE contracts MODIFY `%s` decimal(10,2) DEFAULT NULL',
                $columnName,
            ));
        }
    }

    private function normalizeUsers(): void
    {
        if (!$this->tableExists('users')) {
            return;
        }

        $this->renameColumnIfNeeded(
            'users',
            'user_password_reset_hash',
            'password_reset_hash',
            'ALTER TABLE users CHANGE user_password_reset_hash password_reset_hash char(40) DEFAULT NULL',
        );

        if (
            $this->columnExists('users', 'user_last_login_timestamp')
            && $this->columnType('users', 'user_last_login_timestamp') !== 'datetime'
        ) {
            $this->addSql(
                'ALTER TABLE users MODIFY user_last_login_timestamp '
                . 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            );
        }

        $nullableTimestampColumns = [];
        foreach ([ 'user_last_failed_login', 'user_password_reset_timestamp' ] as $columnName) {
            if (!$this->columnExists('users', $columnName)) {
                continue;
            }

            $column = $this->schemaManager()->introspectTable('users')->getColumn($columnName);
            if (Type::lookupName($column->getType()) !== 'datetime' || $column->getNotnull()) {
                $nullableTimestampColumns[] = sprintf(
                    'MODIFY `%s` timestamp NULL DEFAULT NULL',
                    $columnName,
                );
            }
        }

        if ($nullableTimestampColumns !== []) {
            // Both legacy zero-date defaults must be replaced in one ALTER;
            // strict MySQL rejects an intermediate table with either one left.
            $this->addSql('ALTER TABLE users ' . implode(', ', $nullableTimestampColumns));
        }

        foreach ([ 'user_last_failed_login', 'user_password_reset_timestamp' ] as $columnName) {
            if (!$this->columnExists('users', $columnName)) {
                continue;
            }

            $this->addSql(sprintf(
                "UPDATE users SET `%1\$s` = NULL "
                . "WHERE CAST(`%1\$s` AS CHAR) = '0000-00-00 00:00:00'",
                $columnName,
            ));
        }
    }

    private function normalizeKnownColumns(): void
    {
        if ($this->columnExists('activity', 'ip_address')) {
            $column = $this->schemaManager()->introspectTable('activity')->getColumn('ip_address');
            if (Type::lookupName($column->getType()) === 'string' && ($column->getLength() ?? 0) < 45) {
                $this->addSql('ALTER TABLE activity MODIFY ip_address varchar(45) DEFAULT NULL');
            }
        }

        if ($this->columnExists('pages', 'id')) {
            $column = $this->schemaManager()->introspectTable('pages')->getColumn('id');
            if (!$column->getNotnull()) {
                $this->addSql('ALTER TABLE pages MODIFY id varchar(32) NOT NULL');
            }
        }

        if ($this->columnExists('site_settings', 'setting')) {
            $column = $this->schemaManager()->introspectTable('site_settings')->getColumn('setting');
            if (!$column->getNotnull()) {
                $this->addSql('ALTER TABLE site_settings MODIFY setting varchar(32) NOT NULL');
            }
        }

        if ($this->columnExists('mail_recipients', 'mail_id')) {
            $column = $this->schemaManager()->introspectTable('mail_recipients')->getColumn('mail_id');
            if (Type::lookupName($column->getType()) !== 'integer' || !$column->getNotnull()) {
                $this->addSql('ALTER TABLE mail_recipients MODIFY mail_id int NOT NULL');
            }
        }
    }

    private function cleanOrphanedReferences(): void
    {
        if ($this->hasColumns('activity', [ 'user_id' ]) && $this->hasColumns('users', [ 'user_id' ])) {
            $this->addSql(
                'UPDATE activity entry '
                . 'LEFT JOIN users related ON related.user_id = entry.user_id '
                . 'SET entry.user_id = NULL '
                . 'WHERE entry.user_id IS NOT NULL AND related.user_id IS NULL'
            );
        }

        if (
            $this->hasColumns('mail_attachments', [ 'mail_id' ])
            && $this->hasColumns('mail_schedule', [ 'id' ])
        ) {
            $this->addSql(
                'UPDATE mail_attachments attachment '
                . 'LEFT JOIN mail_schedule related ON related.id = attachment.mail_id '
                . 'SET attachment.mail_id = NULL '
                . 'WHERE attachment.mail_id IS NOT NULL AND related.id IS NULL'
            );
        }

        if (
            $this->hasColumns('mail_attachments', [ 'template_id' ])
            && $this->hasColumns('mail_templates', [ 'id' ])
        ) {
            $this->addSql(
                'UPDATE mail_attachments attachment '
                . 'LEFT JOIN mail_templates related ON related.id = attachment.template_id '
                . 'SET attachment.template_id = NULL '
                . 'WHERE attachment.template_id IS NOT NULL AND related.id IS NULL'
            );
        }

        if (
            $this->hasColumns('mail_recipients', [ 'mail_id' ])
            && $this->hasColumns('mail_schedule', [ 'id' ])
        ) {
            $this->addSql(
                'DELETE recipient FROM mail_recipients recipient '
                . 'LEFT JOIN mail_schedule related ON related.id = recipient.mail_id '
                . 'WHERE recipient.mail_id IS NULL OR related.id IS NULL'
            );
        }

        if ($this->hasColumns('invoices', [ 'contract_id' ]) && $this->hasColumns('contracts', [ 'id' ])) {
            $this->addSql(
                'UPDATE invoices invoice '
                . 'LEFT JOIN contracts related ON related.id = invoice.contract_id '
                . 'SET invoice.contract_id = NULL '
                . 'WHERE invoice.contract_id IS NOT NULL AND related.id IS NULL'
            );
        }

        if ($this->hasColumns('invoice_items', [ 'invoice_id' ]) && $this->hasColumns('invoices', [ 'id' ])) {
            $this->addSql(
                'DELETE item FROM invoice_items item '
                . 'LEFT JOIN invoices related ON related.id = item.invoice_id '
                . 'WHERE related.id IS NULL'
            );
        }

        if (
            $this->hasColumns('role_perm', [ 'role_id', 'perm_id' ])
            && $this->hasColumns('roles', [ 'role_id' ])
            && $this->hasColumns('permissions', [ 'perm_id' ])
        ) {
            $this->addSql(
                'DELETE assignment FROM role_perm assignment '
                . 'LEFT JOIN roles role_record ON role_record.role_id = assignment.role_id '
                . 'LEFT JOIN permissions permission_record ON permission_record.perm_id = assignment.perm_id '
                . 'WHERE role_record.role_id IS NULL OR permission_record.perm_id IS NULL'
            );
        }

        if (
            $this->hasColumns('user_role', [ 'user_id', 'role_id' ])
            && $this->hasColumns('users', [ 'user_id' ])
            && $this->hasColumns('roles', [ 'role_id' ])
        ) {
            $this->addSql(
                'DELETE assignment FROM user_role assignment '
                . 'LEFT JOIN users user_record ON user_record.user_id = assignment.user_id '
                . 'LEFT JOIN roles role_record ON role_record.role_id = assignment.role_id '
                . 'WHERE user_record.user_id IS NULL OR role_record.role_id IS NULL'
            );
        }
    }

    /**
     * @param array{
     *     columns: array<string, string>,
     *     primary: list<string>,
     *     indexes?: array<string, array{columns: list<string>, unique?: bool}>,
     *     foreign_keys?: array<string, array{
     *         columns: list<string>,
     *         foreign_table: string,
     *         foreign_columns: list<string>
     *     }>
     * } $definition
     */
    private function reconcileTable(string $tableName, array $definition): void
    {
        $alreadyExists = $this->tableExists($tableName);
        $this->ensureTable($tableName, $this->createTableSql($tableName, $definition));
        if (!$alreadyExists) {
            return;
        }

        foreach ($definition['columns'] as $columnName => $columnDefinition) {
            if (
                $tableName === 'users'
                && $columnName === 'password_reset_hash'
                && $this->columnExists('users', 'user_password_reset_hash')
            ) {
                continue;
            }

            $this->ensureColumn(
                $tableName,
                $columnName,
                sprintf(
                    'ALTER TABLE `%s` ADD COLUMN `%s` %s',
                    $tableName,
                    $columnName,
                    $columnDefinition,
                ),
            );
        }

        $this->ensurePrimaryKey($tableName, $definition['primary']);

        foreach ($definition['indexes'] ?? [] as $indexName => $index) {
            $this->ensureIndex(
                $tableName,
                $indexName,
                sprintf(
                    'ALTER TABLE `%s` ADD %sINDEX `%s` (%s)',
                    $tableName,
                    ($index['unique'] ?? false) ? 'UNIQUE ' : '',
                    $indexName,
                    $this->columnList($index['columns']),
                ),
            );
        }

        foreach ($definition['foreign_keys'] ?? [] as $constraintName => $foreignKey) {
            $this->ensureForeignKey(
                $tableName,
                $constraintName,
                sprintf(
                    'ALTER TABLE `%s` ADD CONSTRAINT `%s` FOREIGN KEY (%s) REFERENCES `%s` (%s)',
                    $tableName,
                    $constraintName,
                    $this->columnList($foreignKey['columns']),
                    $foreignKey['foreign_table'],
                    $this->columnList($foreignKey['foreign_columns']),
                ),
            );
        }
    }

    /**
     * @param array{
     *     columns: array<string, string>,
     *     primary: list<string>,
     *     indexes?: array<string, array{columns: list<string>, unique?: bool}>,
     *     foreign_keys?: array<string, array{
     *         columns: list<string>,
     *         foreign_table: string,
     *         foreign_columns: list<string>
     *     }>
     * } $definition
     */
    private function createTableSql(string $tableName, array $definition): string
    {
        $parts = [];
        foreach ($definition['columns'] as $columnName => $columnDefinition) {
            $parts[] = sprintf('`%s` %s', $columnName, $columnDefinition);
        }
        $parts[] = sprintf('PRIMARY KEY (%s)', $this->columnList($definition['primary']));

        foreach ($definition['indexes'] ?? [] as $indexName => $index) {
            $parts[] = sprintf(
                '%sKEY `%s` (%s)',
                ($index['unique'] ?? false) ? 'UNIQUE ' : '',
                $indexName,
                $this->columnList($index['columns']),
            );
        }
        foreach ($definition['foreign_keys'] ?? [] as $constraintName => $foreignKey) {
            $parts[] = sprintf(
                'CONSTRAINT `%s` FOREIGN KEY (%s) REFERENCES `%s` (%s)',
                $constraintName,
                $this->columnList($foreignKey['columns']),
                $foreignKey['foreign_table'],
                $this->columnList($foreignKey['foreign_columns']),
            );
        }

        return sprintf(
            "CREATE TABLE IF NOT EXISTS `%s` (\n  %s\n) "
            . 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci',
            $tableName,
            implode(",\n  ", $parts),
        );
    }

    /**
     * @param list<string> $columns
     */
    private function ensurePrimaryKey(string $tableName, array $columns): void
    {
        if (!$this->tableExists($tableName)) {
            return;
        }

        $primaryKey = $this->schemaManager()->introspectTable($tableName)->getPrimaryKey();
        if ($primaryKey === null) {
            $this->addSql(sprintf(
                'ALTER TABLE `%s` ADD PRIMARY KEY (%s)',
                $tableName,
                $this->columnList($columns),
            ));

            return;
        }

        $actualColumns = array_map('strtolower', $primaryKey->getColumns());
        $expectedColumns = array_map('strtolower', $columns);
        $this->abortIf(
            $actualColumns !== $expectedColumns,
            sprintf(
                '%s has an unexpected primary key (%s).',
                $tableName,
                implode(', ', $primaryKey->getColumns()),
            ),
        );
    }

    private function abortIfUnexpectedContractValues(
        string $columnName,
        string $pattern,
        string $type,
    ): void {
        $unexpectedValues = (int) $this->connection->fetchOne(
            sprintf(
                "SELECT COUNT(*) FROM contracts "
                . "WHERE `%1\$s` IS NOT NULL AND TRIM(`%1\$s`) <> '' "
                . "AND TRIM(`%1\$s`) NOT REGEXP ?",
                $columnName,
            ),
            [ $pattern ],
        );

        $this->abortIf(
            $unexpectedValues > 0,
            sprintf(
                'contracts.%s contains %d unsupported %s value(s).',
                $columnName,
                $unexpectedValues,
                $type,
            ),
        );
    }

    private function columnType(string $tableName, string $columnName): string
    {
        $column = $this->schemaManager()->introspectTable($tableName)->getColumn($columnName);

        return Type::lookupName($column->getType());
    }

    /**
     * @param list<string> $columns
     */
    private function hasColumns(string $tableName, array $columns): bool
    {
        if (!$this->tableExists($tableName)) {
            return false;
        }

        $table = $this->schemaManager()->introspectTable($tableName);
        foreach ($columns as $columnName) {
            if (!$table->hasColumn($columnName)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param list<string> $columns
     */
    private function columnList(array $columns): string
    {
        return implode(', ', array_map(
            static fn (string $columnName): string => sprintf('`%s`', $columnName),
            $columns,
        ));
    }
}
