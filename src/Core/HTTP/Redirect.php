<?php


declare(strict_types=1);

namespace App\Core\HTTP;

use App\Core\Config\Config;

class Redirect
{
    public static function to(string $url, bool $permanent = false)
    {
        // session_write_close();
        if (headers_sent() === false) {
            header('Location: ' . Config::get('URL') . $url, true, ($permanent === true) ? 301 : 302);
        }
        exit();
    }
}
