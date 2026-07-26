<?php

declare(strict_types=1);

use PortalCMS\Core\Config\Config;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('portalcms.database_name', (string) Config::get('DB_NAME'));

    $mappings = [];
    foreach ([
        'Activity',
        'Contracts',
        'Email',
        'Events',
        'Invoices',
        'Members',
        'Pages',
        'Products',
        'Settings',
        'Users',
    ] as $feature) {
        $mappings[$feature] = [
            'is_bundle' => false,
            'type' => 'attribute',
            'dir' => sprintf('%%kernel.project_dir%%/src/Features/%s/Entity', $feature),
            'prefix' => sprintf('PortalCMS\Features\%s\Entity', $feature),
            'alias' => $feature,
        ];
    }

    $container->extension('doctrine', [
        'dbal' => [
            'default_connection' => 'default',
            'connections' => [
                'default' => [
                    'driver' => 'pdo_mysql',
                    'host' => Config::get('DB_HOST'),
                    'port' => (int) Config::get('DB_PORT'),
                    'dbname' => '%env(default:portalcms.database_name:MIGRATIONS_TEST_DB)%',
                    'user' => Config::get('DB_USER'),
                    'password' => Config::get('DB_PASS'),
                    'charset' => Config::get('DB_CHARSET'),
                ],
            ],
        ],
        'orm' => [
            'default_entity_manager' => 'default',
            'auto_generate_proxy_classes' => '%kernel.debug%',
            'entity_managers' => [
                'default' => [
                    'connection' => 'default',
                    'auto_mapping' => false,
                    'mappings' => $mappings,
                ],
            ],
        ],
    ]);
};
