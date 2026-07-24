<?php

declare(strict_types=1);

namespace PortalCMS\Core\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\AbstractAsset;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use PortalCMS\Core\Config\Config;
use RuntimeException;

final class DoctrineConfiguration
{
    public static function createEntityManager(): EntityManagerInterface
    {
        $entityPaths = glob(dirname(__DIR__, 2) . '/Features/*/Entity', GLOB_ONLYDIR);
        if ($entityPaths === false || $entityPaths === []) {
            throw new RuntimeException('No feature entity directories were found.');
        }
        sort($entityPaths);

        $ormConfiguration = ORMSetup::createAttributeMetadataConfiguration(
            paths: $entityPaths,
            isDevMode: true,
        );
        $ormConfiguration->setSchemaAssetsFilter(
            static fn (string|AbstractAsset $asset): bool => (
                $asset instanceof AbstractAsset ? $asset->getName() : $asset
            ) !== 'doctrine_migration_versions',
        );

        $connection = DriverManager::getConnection([
            'driver' => 'pdo_mysql',
            'host' => Config::get('DB_HOST'),
            'port' => (int) Config::get('DB_PORT'),
            'dbname' => Config::get('DB_NAME'),
            'user' => Config::get('DB_USER'),
            'password' => Config::get('DB_PASS'),
            'charset' => Config::get('DB_CHARSET'),
        ], $ormConfiguration);

        return new EntityManager($connection, $ormConfiguration);
    }
}
