<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Database;

use PDO;
use PDOException;
use PortalCMS\Core\Config\Config;

/**
 * Database class
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * @return PDO|null
     */
    public static function &conn(): ?PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $type = Config::get('DB_TYPE');
        $host = Config::get('DB_HOST');
        $database = Config::get('DB_NAME');
        $username = Config::get('DB_USER');
        $password = Config::get('DB_PASS');
        $port = Config::get('DB_PORT');
        $charset = Config::get('DB_CHARSET');
        // $DB_COLLATE = Config::get('DB_COLLATE');

        $dsn = $type . ':host=' . $host . ';port=' . $port . ';dbname=' . $database . ';charset=' . $charset;
        $options = [
            //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_STRINGIFY_FETCHES => false
        ];
        /**
         * Check Database connection in try/catch block. Also when PDO is not constructed properly,
         * prevent to exposing database host, username and password in plain text as:
         * PDO->__construct('mysql:host=127....', 'root', '12345678', Array)
         * by throwing custom error message
         */
        try {
            self::$instance = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $exception) {
            error_log('Database connection failed: ' . $exception->getMessage());
            echo 'Database connection can not be established. Please try again later.';
            exit;
        }
        return self::$instance;
    }
}
