<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->import(dirname(__DIR__) . '/src/Core/Controller/', 'attribute');

    $controllerDirectories = glob(
        dirname(__DIR__) . '/src/Features/*/Controller',
        GLOB_ONLYDIR,
    );
    if ($controllerDirectories === false) {
        throw new RuntimeException('Feature controller directories could not be discovered.');
    }
    sort($controllerDirectories);

    foreach ($controllerDirectories as $controllerDirectory) {
        $routes->import($controllerDirectory . '/', 'attribute');
    }
};
