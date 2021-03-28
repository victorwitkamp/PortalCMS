<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security;

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;

class Csrf
{
    public static function makeToken(): string
    {
        $max_time = 60 * 60 * 24;
        $stored_time = Session::get('csrf_token_time');
        $csrf_token = Session::get('csrf_token');
        if (empty($csrf_token) || $max_time + $stored_time <= time()) {
            $csrf_token = md5(uniqid((string)mt_rand(), true));
            Session::set('csrf_token', $csrf_token);
            Session::set('csrf_token_time', time());
        }
        return $csrf_token;
    }

    public static function isTokenValid(): bool
    {
        $token = Request::post('csrf_token');
        $sessiontoken = Session::get('csrf_token');
        if (!empty($token)) {
            var_dump($token);
            var_dump($sessiontoken);
            die;
            return $token === $sessiontoken;
        }
    }
}
