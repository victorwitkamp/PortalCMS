<?php

declare(strict_types=1);

require __DIR__ . '/config/constants.php';
require DIR_VENDOR . 'autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use PortalCMS\Core\Config\Config;

// MIGRATIONS_TEST_DB lets Phase 0 validation point this CLI tool at a
// throwaway database instead of the live one named in config.development.php,
// without ever touching that shared config file.
$dbName = getenv('MIGRATIONS_TEST_DB') !== false ? getenv('MIGRATIONS_TEST_DB') : Config::get('DB_NAME');

$connection = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'host' => Config::get('DB_HOST'),
    'port' => (int) Config::get('DB_PORT'),
    'dbname' => $dbName,
    'user' => Config::get('DB_USER'),
    'password' => Config::get('DB_PASS'),
    'charset' => Config::get('DB_CHARSET'),
]);

$configuration = new ConfigurationArray(require __DIR__ . '/migrations.php');

return DependencyFactory::fromConnection($configuration, new ExistingConnection($connection));
