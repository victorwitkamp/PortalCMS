<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('doctrine_migrations', [
        'connection' => 'default',
        'migrations_paths' => [
            'DoctrineMigrations' => '%kernel.project_dir%/db/migrations',
        ],
        'storage' => [
            'table_storage' => [
                'table_name' => 'doctrine_migration_versions',
                'version_column_name' => 'version',
                'version_column_length' => 191,
                'executed_at_column_name' => 'executed_at',
                'execution_time_column_name' => 'execution_time',
            ],
        ],
        // MySQL implicitly commits DDL and cannot provide atomic rollbacks.
        'all_or_nothing' => false,
        'transactional' => false,
        'check_database_platform' => true,
    ]);
};
