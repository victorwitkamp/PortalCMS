<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use League\Plates\Engine;
use PortalCMS\Core\HTTP\Cookie;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authentication\Service\LoginService;
use PortalCMS\Core\Security\Csrf;
use PortalCMS\Core\User\PasswordReset;
use PortalCMS\Core\View\Text;

/**
 * LoginController
 * Controls everything that is authentication-related
 */
class LoginController
{
    protected $templates;

    //    private $requests = [
    //        'loginSubmit' => 'POST',
    // 'requestPasswordResetSubmit' => 'POST',
    // 'resetSubmit' => 'POST'
    //    ];

    public function __construct(Engine $templates)
    {
        // if (isset($_POST['signupSubmit'])) {
        //     $this->signup($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm_password']);
        // }
        // if (isset($_POST['activateSubmit'])) {
        //     if ($this->activate($_POST['email'], $_POST['code'])) {
        //         Redirect::to('Login');
        //     }
        // }
        $this->templates = $templates;
    }

    public function loginSubmit()
    {
        if (!Csrf::isTokenValid()) {
            Session::add('feedback_negative', 'Invalid CSRF token.');
            return new RedirectResponse('/Login');
        }
        $rememberMe = false;
        if (Request::post('set_remember_me_cookie') === 'on') {
            $rememberMe = true;
        }
        $username = Request::post('user_name');
        $password = Request::post('user_password');
        if (!empty($username) && !empty($password) && LoginService::loginWithPassword($username, $password, $rememberMe)) {
            if (!empty(Request::post('redirect'))) {
                Redirect::to(ltrim(urldecode(Request::post('redirect')), '/'));
            } else {
                return new RedirectResponse('/Home');
            }
        }
        return new RedirectResponse('/Login');
    }

    /**
     */
    public static function loginWithFacebook(int $fbid)
    {
        if (LoginService::loginWithFacebook($fbid)) {
            if (Request::post('redirect')) {
                Redirect::to(ltrim(urldecode(Request::post('redirect')), '/'));
            } else {
                return new RedirectResponse('/Home');
            }
        }
        if (Request::post('redirect')) {
            Redirect::to('Login/?redirect=' . ltrim(urlencode(Request::post('redirect')), '/'));
        } else {
            return new RedirectResponse('/Login');
        }
    }

    public static function requestPasswordResetSubmit()
    {
        if (PasswordReset::requestPasswordReset((string) Request::post('user_name_or_email'))) {
            return new RedirectResponse('/Login');
        }
    }

    public static function resetSubmit()
    {
        $username = (string) Request::post('username');
        $resetHash = (string) Request::post('password_reset_hash');
        if (PasswordReset::verifyPasswordReset($username, $resetHash)) {
            $passwordHash = password_hash(base64_encode((string) Request::post('password')), PASSWORD_DEFAULT);
            if (PasswordReset::saveNewUserPassword($username, $passwordHash, $resetHash)) {
                Session::add('feedback_positive', Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL'));
                return new RedirectResponse('/Login');
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CHANGE_FAILED'));
                return new RedirectResponse('/Login/PasswordReset');
            }
        }
    }

    public function index()
    {
        if (Authentication::userIsLoggedIn()) {
            Session::add('feedback_positive', 'You are already logged in.');
            if (Request::post('redirect')) {
                Redirect::to(ltrim(urldecode(Request::post('redirect')), '/'));
            } else {
                return new RedirectResponse('/Home');
            }
        } elseif (self::loginWithCookie()) {
            Session::add('feedback_positive', 'You are automatically logged in using a cookie.');
            if (Request::post('redirect')) {
                Redirect::to(ltrim(urldecode(Request::post('redirect')), '/'));
            } else {
                return new RedirectResponse('/Home');
            }
        } else {
            return new HtmlResponse($this->templates->render('Pages/Login/Index'));
        }
    }

    public static function loginWithCookie(): bool
    {
        $cookie = Request::cookie('remember_me');
        if (!empty($cookie) && LoginService::loginWithCookie((string) $cookie)) {
            return true;
        }
        // if not, delete cookie (outdated? attack?) and route user to login form to prevent infinite login loops
        Cookie::delete();
        return false;
    }

    public function requestPasswordReset()
    {
        return new HtmlResponse($this->templates->render('Pages/Login/RequestPasswordReset'));
    }

    public function passwordReset()
    {
        return new HtmlResponse($this->templates->render('Pages/Login/PasswordReset'));
    }

    public function activate()
    {
        return new HtmlResponse($this->templates->render('Pages/Login/Activate'));
    }
}
