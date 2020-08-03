<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Database;

use PDO;
use PDOException;
use PortalCMS\Controllers\ErrorController;
use PortalCMS\Core\Config\Config;

/**
 * Database class
 */
class Database
{
    /**
     * @return PDO|null
     */
    public static function &conn(): ?PDO
    {
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
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ];
        $conn = null;
        if ($conn === null) {
            /**
             * Check Database connection in try/catch block. Also when PDO is not constructed properly,
             * prevent to exposing database host, username and password in plain text as:
             * PDO->__construct('mysql:host=127....', 'root', '12345678', Array)
             * by throwing custom error message
             */
            try {
                $conn = new PDO(
                    $dsn,
                    $username,
                    $password,
                    $options
                );
            } catch (PDOException $exception) {
                echo 'Database connection can not be estabilished. Please try again later.' . '<br>';
                echo 'Error message: ' . $exception->getMessage();
                echo '<br>';
                echo 'Error code: ' . $exception->getCode(); // getCode() returns a string.
                exit;
            }
        }
        return $conn;
    }
}
