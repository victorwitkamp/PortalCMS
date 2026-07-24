<?php

declare(strict_types=1);

use PortalCMS\Core\Config\Config;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'secret' => hash_hmac(
            'sha256',
            'PortalCMS FrameworkBundle',
            (string) Config::get('ENCRYPTION_KEY'),
        ),
        'handle_all_throwables' => true,
        'router' => [
            'utf8' => true,
        ],
        'session' => [
            'enabled' => true,
            'storage_factory_id' => 'session.storage.factory.native',
            'name' => 'PORTALCMSSESSID',
            'cookie_lifetime' => 0,
            'cookie_path' => '/',
            'cookie_secure' => 'auto',
            'cookie_httponly' => true,
            'cookie_samesite' => 'lax',
            'use_cookies' => true,
            'gc_maxlifetime' => 1800,
        ],
        'csrf_protection' => true,
        'serializer' => [
            'enabled' => true,
            'enable_attributes' => true,
        ],
        'validation' => [
            'enabled' => true,
            'enable_attributes' => true,
        ],
        'property_access' => [
            'enabled' => true,
        ],
    ]);
};
