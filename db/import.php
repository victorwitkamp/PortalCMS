<?php

declare(strict_types=1);

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PortalCMS\Core\Config\Config;

require dirname(__DIR__) . '/config/constants.php';
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Imports a dump from a pre-Doctrine PortalCMS instance into a new database.
 *
 * Historical database-name directives are redirected to the requested target.
 * The guarded Doctrine baseline reconciles the legacy schema directly with the
 * current application schema.
 */
final class LegacyDatabaseImporter
{
    private readonly string $projectRoot;

    public function __construct()
    {
        $this->projectRoot = dirname(__DIR__);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function run(array $options): void
    {
        $dumpPath = $this->requiredOption($options, 'dump');
        $databaseName = $this->requiredOption($options, 'database');

        $this->validateDatabaseName($databaseName);
        $this->validateTarget($databaseName);

        $resolvedDumpPath = realpath($dumpPath);
        if ($resolvedDumpPath === false || !is_file($resolvedDumpPath) || !is_readable($resolvedDumpPath)) {
            throw new RuntimeException(sprintf('The dump is not a readable file: %s', $dumpPath));
        }
        if (filesize($resolvedDumpPath) === 0) {
            throw new RuntimeException('The dump file is empty.');
        }

        $mysqlBinary = $this->findMysqlBinary(
            isset($options['mysql-bin']) && is_string($options['mysql-bin'])
                ? $options['mysql-bin']
                : null,
        );

        fwrite(STDOUT, "Preparing an isolated import for database {$databaseName}.\n");
        $sanitizedDumpPath = null;
        $clientOptionPath = null;
        $serverConnection = null;
        $databaseCreated = false;

        try {
            $sanitizedDumpPath = $this->prepareDump($resolvedDumpPath, $databaseName);
            $clientOptionPath = $this->createClientOptionFile();
            $serverConnection = $this->createServerConnection();
            $this->createDatabase($serverConnection, $databaseName);
            $databaseCreated = true;
            fwrite(STDOUT, "Importing the SQL dump.\n");
            $this->runCommand([
                $mysqlBinary,
                '--defaults-extra-file=' . $clientOptionPath,
                '--protocol=TCP',
                '--database=' . $databaseName,
                '--binary-mode=1',
            ], $sanitizedDumpPath);
            $this->reconcileLegacyTableNames($serverConnection, $databaseName);

            fwrite(STDOUT, "Applying the squashed Doctrine baseline.\n");
            $this->runMigrations($databaseName);

            $this->showMigrationStatus($databaseName);
            fwrite(STDOUT, "Import complete. The database is at the latest Doctrine migration.\n");
        } catch (Throwable $exception) {
            fwrite(
                STDERR,
                $databaseCreated
                    ? "Import failed. The partially imported database was retained for inspection: {$databaseName}\n"
                    : "Import failed before a new target database was created.\n",
            );

            throw $exception;
        } finally {
            $serverConnection?->close();
            if ($sanitizedDumpPath !== null) {
                @unlink($sanitizedDumpPath);
            }
            if ($clientOptionPath !== null) {
                @unlink($clientOptionPath);
            }
        }
    }

    /**
     * @param array<string, mixed> $options
     */
    private function requiredOption(array $options, string $name): string
    {
        $value = $options[$name] ?? null;
        if (!is_string($value) || trim($value) === '') {
            throw new InvalidArgumentException(sprintf('Missing required option --%s.', $name));
        }

        return $value;
    }

    private function validateDatabaseName(string $databaseName): void
    {
        if (preg_match('/^[A-Za-z0-9_]+$/', $databaseName) !== 1) {
            throw new InvalidArgumentException(
                'The target database name may contain only letters, numbers, and underscores.',
            );
        }
    }

    private function validateTarget(string $databaseName): void
    {
        $configuredDatabase = (string) Config::get('DB_NAME');
        if (strcasecmp($databaseName, $configuredDatabase) === 0) {
            throw new InvalidArgumentException(
                'The importer will not overwrite the database configured in config.development.php.',
            );
        }
    }

    private function prepareDump(string $sourcePath, string $databaseName): string
    {
        $source = fopen($sourcePath, 'rb');
        if ($source === false) {
            throw new RuntimeException('Could not open the dump for reading.');
        }

        $targetPath = tempnam(sys_get_temp_dir(), 'portalcms_import_');
        if ($targetPath === false) {
            fclose($source);
            throw new RuntimeException('Could not allocate a temporary dump file.');
        }

        $target = fopen($targetPath, 'wb');
        if ($target === false) {
            fclose($source);
            @unlink($targetPath);
            throw new RuntimeException('Could not open the temporary dump for writing.');
        }

        fwrite($target, sprintf("USE `%s`;\n", $databaseName));
        $lineNumber = 0;

        $failed = false;
        try {
            while (($line = fgets($source)) !== false) {
                ++$lineNumber;

                if (preg_match(
                    '/^\s*(?:\/\*!\d+\s+)?(?:DROP|ALTER)\s+DATABASE\b/i',
                    $line,
                ) === 1) {
                    throw new RuntimeException(sprintf(
                        'Unsafe database-level statement found on dump line %d.',
                        $lineNumber,
                    ));
                }

                if (preg_match(
                    '/^\s*(?:\/\*!\d+\s+)?CREATE\s+DATABASE\b/i',
                    $line,
                ) === 1) {
                    if (preg_match('/;\s*(?:\*\/)?\s*$/', $line) !== 1) {
                        throw new RuntimeException(sprintf(
                            'A multi-line CREATE DATABASE statement is not supported (line %d).',
                            $lineNumber,
                        ));
                    }

                    fwrite($target, "-- Database creation redirected by the PortalCMS importer.\n");
                    continue;
                }

                if (preg_match('/^\s*(?:\/\*!\d+\s+)?USE\s+/i', $line) === 1) {
                    if (preg_match('/;\s*(?:\*\/)?\s*$/', $line) !== 1) {
                        throw new RuntimeException(sprintf(
                            'A multi-line USE statement is not supported (line %d).',
                            $lineNumber,
                        ));
                    }

                    fwrite($target, sprintf("USE `%s`;\n", $databaseName));
                    continue;
                }

                fwrite($target, $line);
            }

            if (!feof($source)) {
                throw new RuntimeException('The dump could not be read completely.');
            }
        } catch (Throwable $exception) {
            $failed = true;
            throw $exception;
        } finally {
            fclose($source);
            fclose($target);
            if ($failed) {
                @unlink($targetPath);
            }
        }

        return $targetPath;
    }

    private function createClientOptionFile(): string
    {
        $path = tempnam(sys_get_temp_dir(), 'portalcms_mysql_');
        if ($path === false) {
            throw new RuntimeException('Could not allocate a temporary MySQL option file.');
        }

        $content = implode("\n", [
            '[client]',
            'host=' . $this->quoteOptionValue((string) Config::get('DB_HOST')),
            'port=' . (int) Config::get('DB_PORT'),
            'user=' . $this->quoteOptionValue((string) Config::get('DB_USER')),
            'password=' . $this->quoteOptionValue((string) Config::get('DB_PASS')),
            'default-character-set=' . $this->quoteOptionValue((string) Config::get('DB_CHARSET')),
            '',
        ]);

        if (file_put_contents($path, $content, LOCK_EX) === false) {
            @unlink($path);
            throw new RuntimeException('Could not write the temporary MySQL option file.');
        }
        @chmod($path, 0600);

        return $path;
    }

    private function quoteOptionValue(string $value): string
    {
        if (str_contains($value, "\r") || str_contains($value, "\n")) {
            throw new RuntimeException('MySQL configuration values may not contain line breaks.');
        }

        return '"' . str_replace([ '\\', '"' ], [ '\\\\', '\\"' ], $value) . '"';
    }

    private function createServerConnection(): Connection
    {
        return DriverManager::getConnection([
            'driver' => 'pdo_mysql',
            'host' => Config::get('DB_HOST'),
            'port' => (int) Config::get('DB_PORT'),
            'user' => Config::get('DB_USER'),
            'password' => Config::get('DB_PASS'),
            'charset' => Config::get('DB_CHARSET'),
        ]);
    }

    private function createDatabase(Connection $connection, string $databaseName): void
    {
        $exists = (bool) $connection->fetchOne(
            'SELECT EXISTS('
            . 'SELECT 1 FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?'
            . ')',
            [ $databaseName ],
        );
        if ($exists) {
            throw new RuntimeException(sprintf(
                'The target database already exists; choose a new empty database name: %s',
                $databaseName,
            ));
        }

        $charset = (string) Config::get('DB_CHARSET');
        if (preg_match('/^[A-Za-z0-9_]+$/', $charset) !== 1) {
            throw new RuntimeException('DB_CHARSET contains an unsupported value.');
        }

        $quotedDatabase = $connection->getDatabasePlatform()->quoteSingleIdentifier($databaseName);
        $connection->executeStatement(sprintf(
            'CREATE DATABASE %s CHARACTER SET %s',
            $quotedDatabase,
            $charset,
        ));
    }

    private function reconcileLegacyTableNames(Connection $connection, string $databaseName): void
    {
        $hasLegacyContracts = $this->tableExists($connection, $databaseName, 'band_contracts');
        if (!$hasLegacyContracts) {
            return;
        }

        if ($this->tableExists($connection, $databaseName, 'contracts')) {
            throw new RuntimeException(
                'The dump contains both band_contracts and contracts; merge those tables before importing.',
            );
        }

        $platform = $connection->getDatabasePlatform();
        $quotedDatabase = $platform->quoteSingleIdentifier($databaseName);
        $connection->executeStatement(sprintf(
            'RENAME TABLE %1$s.%2$s TO %1$s.%3$s',
            $quotedDatabase,
            $platform->quoteSingleIdentifier('band_contracts'),
            $platform->quoteSingleIdentifier('contracts'),
        ));
        fwrite(STDOUT, "Renamed legacy table band_contracts to contracts.\n");
    }

    private function tableExists(
        Connection $connection,
        string $databaseName,
        string $tableName,
    ): bool {
        return (bool) $connection->fetchOne(
            'SELECT EXISTS('
            . 'SELECT 1 FROM information_schema.TABLES '
            . 'WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?'
            . ')',
            [ $databaseName, $tableName ],
        );
    }

    private function runMigrations(string $databaseName): void
    {
        $command = [
            PHP_BINARY,
            $this->projectRoot . '/vendor/bin/doctrine-migrations',
            'migrate',
        ];
        $command[] = '--no-interaction';

        $this->withMigrationDatabase(
            $databaseName,
            fn (): int => $this->runCommand($command),
        );
    }

    private function showMigrationStatus(string $databaseName): void
    {
        $this->withMigrationDatabase(
            $databaseName,
            fn (): int => $this->runCommand([
                PHP_BINARY,
                $this->projectRoot . '/vendor/bin/doctrine-migrations',
                'status',
            ]),
        );
    }

    /**
     * @param callable(): int $operation
     */
    private function withMigrationDatabase(string $databaseName, callable $operation): void
    {
        $previousDatabase = getenv('MIGRATIONS_TEST_DB');
        putenv('MIGRATIONS_TEST_DB=' . $databaseName);

        try {
            $operation();
        } finally {
            if ($previousDatabase === false) {
                putenv('MIGRATIONS_TEST_DB');
            } else {
                putenv('MIGRATIONS_TEST_DB=' . $previousDatabase);
            }
        }
    }

    /**
     * @param list<string> $command
     */
    private function runCommand(array $command, ?string $stdinPath = null): int
    {
        $descriptors = [
            0 => $stdinPath === null
                ? [ 'file', PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null', 'r' ]
                : [ 'file', $stdinPath, 'r' ],
            1 => STDOUT,
            2 => STDERR,
        ];

        $process = proc_open($command, $descriptors, $pipes, $this->projectRoot);
        if (!is_resource($process)) {
            throw new RuntimeException(sprintf('Could not start command: %s', $command[0]));
        }

        $exitCode = proc_close($process);
        if ($exitCode !== 0) {
            throw new RuntimeException(sprintf(
                'Command failed with exit code %d: %s',
                $exitCode,
                implode(' ', array_map(escapeshellarg(...), $command)),
            ));
        }

        return $exitCode;
    }

    private function findMysqlBinary(?string $explicitPath): string
    {
        if ($explicitPath !== null) {
            $resolvedPath = realpath($explicitPath);
            if ($resolvedPath === false || !is_file($resolvedPath)) {
                throw new RuntimeException(sprintf('MySQL client not found: %s', $explicitPath));
            }

            return $resolvedPath;
        }

        $candidates = [];
        $environmentPath = getenv('MYSQL_BIN');
        if (is_string($environmentPath) && $environmentPath !== '') {
            $candidates[] = $environmentPath;
        }

        $executable = PHP_OS_FAMILY === 'Windows' ? 'mysql.exe' : 'mysql';
        foreach (explode(PATH_SEPARATOR, (string) getenv('PATH')) as $directory) {
            if ($directory !== '') {
                $candidates[] = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $executable;
            }
        }

        if (PHP_OS_FAMILY === 'Windows') {
            $installations = glob('C:/Program Files/MySQL/MySQL Server */bin/mysql.exe') ?: [];
            rsort($installations);
            array_push($candidates, ...$installations);
            array_push(
                $candidates,
                'C:/xampp/mysql/bin/mysql.exe',
                'C:/wamp64/bin/mysql/mysql8.0.*/bin/mysql.exe',
            );
        } else {
            array_push($candidates, '/usr/bin/mysql', '/usr/local/bin/mysql');
        }

        foreach ($candidates as $candidate) {
            $matches = str_contains($candidate, '*') ? (glob($candidate) ?: []) : [ $candidate ];
            foreach ($matches as $match) {
                $resolvedPath = realpath($match);
                if ($resolvedPath !== false && is_file($resolvedPath)) {
                    return $resolvedPath;
                }
            }
        }

        throw new RuntimeException(
            'MySQL client not found. Pass its path with --mysql-bin or MYSQL_BIN.',
        );
    }
}

function printUsage(): void
{
    fwrite(STDOUT, <<<'TEXT'
Usage:
  php db/import.php --dump=<backup.sql> --database=<new_database> [options]

Options:
  --mysql-bin=<path>  Path to mysql/mysql.exe when it is not on PATH.
  --help              Show this help.

The target database must not already exist and may not be the database from
config/config.development.php. The squashed Doctrine baseline reconciles a
legacy dump directly with the current schema, leaving no pending migrations.

TEXT);
}

$options = getopt('', [ 'dump:', 'database:', 'mysql-bin:', 'help' ]);
if ($options === false) {
    fwrite(STDERR, "Could not parse command-line options.\n");
    exit(2);
}

if (array_key_exists('help', $options)) {
    printUsage();
    exit(0);
}

try {
    (new LegacyDatabaseImporter())->run($options);
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage() . "\n");
    exit(1);
}
