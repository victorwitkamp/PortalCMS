<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Config;

class Config
{
    public static $config;

    public static function get(string $key)
    {
        if (!self::$config) {
            $config_file = DIR_CONFIG . 'config.development.php';
            if (!is_file($config_file)) {
                echo 'No configuration file could be found. Please check your configuration file in the "config" folder.<br><br>';
                echo 'You can find an example configuration file (config.development.php.example) in the "config" folder as well. Rename this file to config.development.php and use it as a starting point.';
                die;
            }
            self::$config = include $config_file;
        }

        return self::$config[$key];
    }
}
