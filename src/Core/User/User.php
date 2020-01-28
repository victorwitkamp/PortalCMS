<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

/**
 * User
 * Handles all the PUBLIC profile stuff. This is not for getting data of the logged in user, it's more for handling
 * data of all the other users. Useful for display profile information, creating user lists etc.
 */
class User
{
    /**
     * Edit the user's name, provided in the editing form
     * @param string $newUsername The new username
     * @return bool success status
     */
    public static function editUsername(string $newUsername): bool
    {
        if ($newUsername === Session::get('user_name')) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_SAME_AS_OLD_ONE'));
        } elseif (!preg_match('/^[a-zA-Z0-9]{2,64}$/', $newUsername)) {
            // username cannot be empty and must be azAZ09 and 2-64 characters
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
        } else {
            // clean the input, strip usernames longer than 64 chars (maybe fix this ?)
            $username = substr(strip_tags($newUsername), 0, 64);
            // check if new username already exists
            if (UserPDOReader::usernameExists($username)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_ALREADY_TAKEN'));
            } elseif (!UserPDOWriter::updateUsername(Session::get('user_id'), $username)) {
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
