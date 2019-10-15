<?php

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
        if (Auth::userIsLoggedIn()) {
            $user_id = Session::get('user_id');
            if (!empty($user_id)) {
                UserMapper::clearRememberMeToken($user_id);
                if (Cookie::delete()) {
                    if (Session::destroy()) {
                        Session::init();
                        Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_SUCCESSFUL'));
                        Redirect::login();
                    }
                }
            }
        } else {
            if (Session::destroy()) {
                Session::init();
                Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_INVALID'));
                Redirect::login();
            }
        }
    }
}
