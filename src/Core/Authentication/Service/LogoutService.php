<?php

namespace PortalCMS\Core\Authentication\Service;

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\HTTP\Cookie;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\User\UserMapper;

/**
 * LogoutService
 */
class LogoutService
{
    /**
     * Log out process: delete cookie, delete session
     */
    public static function logout()
    {
        if (Authentication::userIsLoggedIn()) {
            $user_id = Session::get('user_id');
            if (!empty($user_id)) {
                UserMapper::clearRememberMeToken($user_id);
                if (Cookie::delete()) {
                    Session::destroy();
                    Session::init();
                    Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_SUCCESSFUL'));
                    Redirect::login();
                    return true;
                }
            }
        }
        Session::destroy();
        Session::init();
        Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_INVALID'));
        Redirect::login();
        return false;
    }
}
