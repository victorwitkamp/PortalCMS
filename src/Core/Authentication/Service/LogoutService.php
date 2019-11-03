<?php

namespace PortalCMS\Core\Authentication\Service;

use PortalCMS\Core\View\Text;
use PortalCMS\Core\HTTP\Cookie;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOWriter;
use PortalCMS\Core\Authentication\Authentication;

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
                UserPDOWriter::clearRememberMeToken($user_id);
                if (Cookie::delete()) {
                    Session::destroy();
                    Session::init();
                    Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_SUCCESSFUL'));
                    Redirect::to('login');
                    return true;
                }
            }
        }
        Session::destroy();
        Session::init();
        Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_INVALID'));
        Redirect::to('login');
        return false;
    }
}
