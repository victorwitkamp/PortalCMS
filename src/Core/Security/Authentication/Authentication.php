<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authentication;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;

class Authentication
{
    public static function checkAuthentication(): void
    {
        Session::init();
        if (!self::userIsLoggedIn()) {
            Session::destroy();
            Session::add('feedback_negative', 'You need to log-in first.');
            $REQUEST_URI = urlencode(ltrim($_SERVER['REQUEST_URI'], '/'));
            Redirect::to('Login/?redirect=' . $REQUEST_URI);
            // to prevent fetching views via cURL (which "ignores" the header-redirect above) we leave the application
            // the hard way, via exit(). @see https://github.com/panique/php-login/issues/453
            exit();
        }
        // Hook to check is a cookie exists and if it matches a remember me token in the database.
        // if (!Cookie::isValid()) {
        // }
    }

    public static function userIsLoggedIn(): bool
    {
        return Session::get('user_logged_in') ? true : false;
    }
}
