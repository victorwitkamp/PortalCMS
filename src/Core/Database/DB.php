<?php

namespace PortalCMS\Core\Database;

use PDO;
use PDOException;
use PortalCMS\Core\Config\Config;

/**
 * Class DB
 *
 * Database connection
 */
class DB
{

    public static function &conn()
    {
        $DB_TYPE = Config::get('DB_TYPE');
        $DB_HOST = Config::get('DB_HOST');
        $DB_NAME = Config::get('DB_NAME');
        $DB_USER = Config::get('DB_USER');
        $DB_PASS = Config::get('DB_PASS');
        $DB_PORT = Config::get('DB_PORT');
        $DB_CHARSET = Config::get('DB_CHARSET');
        // $DB_COLLATE = Config::get('DB_COLLATE');

        $dsn = $DB_TYPE . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET;
        $options = array(
            //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        $conn = null;
        if ($conn == null) {
            /**
             * Check DB connection in try/catch block. Also when PDO is not constructed properly,
             * prevent to exposing database host, username and password in plain text as:
             * PDO->__construct('mysql:host=127....', 'root', '12345678', Array)
             * by throwing custom error message
             */
            try {
                $conn = new PDO(
                    $dsn,
                    $DB_USER,
                    $DB_PASS,
                    $options
                );
            } catch (PDOException $e) {
                // Echo custom message. Echo error code gives you some info.
                echo 'Database connection can not be estabilished. Please try again later.' . '<br>';
                echo 'Error message: ' . $e->getMessage();
                echo '<br>';
                echo 'Error code: ' . $e->getCode();

                // Stop application :(
                // No connection, reached limit connections etc. so no point to keep it running
                exit;
            }
        }
        return $conn;
    }
}
