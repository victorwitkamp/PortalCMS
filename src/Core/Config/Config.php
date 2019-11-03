<?php
declare(strict_types=1);

namespace PortalCMS\Core\Config;

/**
 * Class Config
 *
 * Gets the configuration
 */
class Config
{
    // this is public to allow better Unit Testing
    public static $config;

    public static function get($key)
    {
        if (!self::$config) {

            $config_file = DIR_ROOT . 'config/config.' . Environment::get() . '.php';

            if (!file_exists($config_file)) {
                // return false;
                echo 'No configuration file could be found. Please check your configuration file in the "config" folder.<br><br>';
                echo 'You can find an example configuration file (config.development.php.example) in the "config" folder as well. Rename this file to config.development.php and use it as a starting point.';
                die;
            }

            self::$config = include $config_file;
        }

        return self::$config[$key];
    }
}
