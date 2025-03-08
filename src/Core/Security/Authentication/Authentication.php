<?php


declare(strict_types=1);

namespace App\Core\Security\Authentication;

use App\Core\HTTP\Redirect;
use App\Core\Session\Session;

class Authentication
{
    public static function checkAuthentication()
    {
        Session::init();
        if (!self::userIsLoggedIn()) {
            // Session::destroy();
            //todo $this->addFlash('danger','You need to log-in first.');

            // Redirect::to('Login/?redirect='.ltrim(urlencode($_SERVER['REQUEST_URI']), '/'));
            Redirect::to('Login/?redirect=' . urlencode($_SERVER['REQUEST_URI']));

            // to prevent fetching views via cURL (which "ignores" the header-redirect above) we leave the application
            // the hard way, via exit(). @see https://github.com/panique/php-login/issues/453
            // this is not optimal and will be fixed in future releases
            exit();
        }

        // Hook to check is a cookie exists and if it matches a remember me token in the database.
        // if (!Cookie::isValid()) {
        // }
    }

    public static function userIsLoggedIn(): bool
    {
        return (Session::get('user_logged_in') ? true : false);
    }
}
