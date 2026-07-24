<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Kernel;

$_SERVER['APP_ENV'] ??= getenv('APPLICATION_ENV') ?: 'development';
$_SERVER['APP_RUNTIME_OPTIONS'] ??= [
    'prod_envs' => [ 'prod', 'production' ],
];

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';
require_once dirname(__DIR__) . '/config/bootstrap.php';

return static function (array $context): Kernel {
    return new Kernel(
        (string) $context['APP_ENV'],
        (bool) $context['APP_DEBUG'],
    );
};
