<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

class User
{
    public static function editUsername(string $newUsername): bool
    {
        if ($newUsername === Session::get('user_name')) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_SAME_AS_OLD_ONE'));
        } elseif (!preg_match('/^[a-zA-Z0-9]{2,64}$/', $newUsername)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
        } else {
            $username = substr(strip_tags($newUsername), 0, 64);
            if (UserMapper::usernameExists($username)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_ALREADY_TAKEN'));
            } elseif (!UserMapper::updateUsername((int) Session::get('user_id'), $username)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
            } else {
                Session::set('user_name', $username);
                Session::add('feedback_positive', Text::get('FEEDBACK_USERNAME_CHANGE_SUCCESSFUL'));
                Redirect::to('Account');
                return true;
            }
        }
        return false;
    }
}
