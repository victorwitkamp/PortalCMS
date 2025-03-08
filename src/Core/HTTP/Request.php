<?php


declare(strict_types=1);

namespace App\Core\HTTP;

use function is_string;

class Request
{
    public static function post(string $key, bool $clean = false)
    {
        if (isset($_POST[$key]) && !empty($_POST[$key])) {
            if ($clean && is_string($key)) {
                $return = trim(strip_tags($_POST[$key]));
            } else {
                $return = $_POST[$key];
            }
            if (!empty($return)) {
                return $return;
            }
        }
        return null;
    }

    public static function get(string $key = null)
    {
        return $_GET[$key] ?? null;
    }

    public static function cookie(string $key = null)
    {
        return $_COOKIE[$key] ?? null;
    }

    public static function files(string $key = null)
    {
        return $_FILES[$key] ?? null;
    }
}
