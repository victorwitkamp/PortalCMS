<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security;

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;

/**
 * Cross Site Request Forgery Class
 */

class Csrf
{
    /**
     * Instructions:
     *
     * At your form, before the submit button put:
     * <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
     *
     * This validation needed in the controller action method to validate CSRF token submitted with the form:
     *
     * if (!Csrf::isTokenValid()) {
     *     LogoutService::logout();
     *     Redirect::to('Home');
     *     exit();
     * }
     *
     * To get simpler code it might be better to put the logout, redirect, exit into an own (static) method.
     */

    /**
     * get CSRF token and generate a new one if expired
     *
     * @access public
     * @static static method
     * @return string
     */
    public static function makeToken(): string
    {
        // token is valid for 1 day
        $max_time    = 60 * 60 * 24;
        $stored_time = Session::get('csrf_token_time');
        $csrf_token  = Session::get('csrf_token');

        if (empty($csrf_token) || $max_time + $stored_time <= time()) {
            $newToken = md5(uniqid((string) mt_rand(), true));
            Session::set('csrf_token', $newToken);
            Session::set('csrf_token_time', time());
        }

        return $newToken;
    }

    /**
     * checks if CSRF token in session is same as in the form submitted
     *
     * @access public
     * @static static method
     * @return bool
     */
    public static function isTokenValid(): bool
    {
        $token = Request::post('csrf_token');
        return $token === Session::get('csrf_token') && !empty($token);
    }
}
