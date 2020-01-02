<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Cookie;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authentication\Service\LoginService;
use PortalCMS\Core\Security\Csrf;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\PasswordReset;

/**
 * LoginController
 * Controls everything that is authentication-related
 */
class LoginController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class. The parent::__construct thing is necessary to
     * put checkAuthentication in here to make an entire controller only usable for logged-in users (for sure not
     * needed in the LoginController).
     */
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['loginSubmit'])) {
            self::loginWithPassword();
        }
        // if (isset($_POST['signupSubmit'])) {
        //     $this->signup($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm_password']);
        // }
        // if (isset($_POST['activateSubmit'])) {
        //     if ($this->activate($_POST['email'], $_POST['code'])) {
        //         Redirect::to('Login');
        //     }
        // }
        if (isset($_POST['requestPasswordReset'])) {
            if (PasswordReset::requestPasswordReset($_POST['user_name_or_email'])) {
                Redirect::to('Login');
            }
        }
        if (isset($_POST['resetSubmit'])) {
            if (PasswordReset::verifyPasswordReset($_POST['username'], $_POST['password_reset_hash'])) {
                $user_password_hash = password_hash(base64_encode($_POST['password']), PASSWORD_DEFAULT);
                if (PasswordReset::saveNewUserPassword($_POST['username'], $user_password_hash, $_POST['password_reset_hash'])) {
                    Redirect::to('Login');
                } else {
                    Redirect::to('Login/passwordReset.php');
                }
            }
        }
    }

    /**
     * Index, default action (shows the login form), when you do login/index
     */
    public function index()
    {
        if (Authentication::userIsLoggedIn()) {
            Session::add('feedback_positive', 'You are already logged in.');
            if (Request::post('redirect')) {
                Redirect::to(ltrim(urldecode(Request::post('redirect')), '/'));
            } else {
                Redirect::to('Home');
            }
        } else {
            if (self::loginWithCookie()) {
                Session::add('feedback_positive', 'You are automatically logged in using a cookie.');
                Redirect::to('Home');
            } else {
                $templates = new Engine(DIR_VIEW);
                echo $templates->render('Pages/Login/indexNew');
            }
        }
    }

    public function requestPasswordReset()
    {
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Login/RequestPasswordReset');
    }

    public function passwordReset()
    {
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Login/PasswordReset');
    }

    public function activate()
    {
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Login/Activate');
    }

    /**
     * The login action, when you do login/Login
     */
    public static function loginWithPassword()
    {
        if (!Csrf::isTokenValid()) {
            Session::add('feedback_negative', 'Invalid CSRF token.');
            Redirect::to('Login');
            return false;
        }
        $rememberMe = false;
        if (Request::post('set_remember_me_cookie') === 'on') {
            $rememberMe = true;
        }
        $username = Request::post('user_name');
        $password = Request::post('user_password');
        if (!empty($username) && (!empty($password)) && LoginService::loginWithPassword($username, $password, $rememberMe)) {
            if (Request::post('redirect')) {
                Redirect::to(ltrim(urldecode(Request::post('redirect')), '/'));
            } else {
                Redirect::to('Home');
            }
            return true;
        }
        Redirect::to('Login');
        return false;
    }

    /**
     * Login with cookie
     */
    public static function loginWithCookie()
    {
        if (LoginService::loginWithCookie(Request::cookie('remember_me'))) {
            return true;
        }
        // if not, delete cookie (outdated? attack?) and route user to login form to prevent infinite login loops
        Cookie::delete();
        return false;
    }

    /**
     * Login with Facebook
     * @param $fbid
     */
    public static function loginWithFacebook(int $fbid)
    {
        if (LoginService::loginWithFacebook($fbid)) {
            if (Request::post('redirect')) {
                Redirect::to(ltrim(urldecode(Request::post('redirect')), '/'));
            } else {
                Redirect::to('Home');
            }
            return true;
        }
        if (Request::post('redirect')) {
            Redirect::to('Login/?redirect=' . ltrim(urlencode(Request::post('redirect')), '/'));
        } else {
            Redirect::to('Login');
        }
        return false;
    }
}
