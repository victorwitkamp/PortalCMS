<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $paths = [
        '%kernel.project_dir%/src/View' => 'View',
    ];

    foreach ([
        'Activity',
        'Contracts',
        'Diagnostics',
        'Email',
        'Events',
        'Home',
        'Invoices',
        'Members',
        'Pages',
        'Settings',
        'Users',
    ] as $feature) {
        $paths[sprintf('%%kernel.project_dir%%/src/Features/%s/View/Templates', $feature)] = $feature;
    }

    $container->extension('twig', [
        'default_path' => '%kernel.project_dir%/src/View',
        'file_name_pattern' => '*.twig',
        'strict_variables' => '%kernel.debug%',
        'paths' => $paths,
    ]);
};
