<?php

/**
 * User
 * Handles all the PUBLIC profile stuff. This is not for getting data of the logged in user, it's more for handling
 * data of all the other users. Useful for display profile information, creating user lists etc.
 */
class User
{
    /**
     * Edit the user's name, provided in the editing form
     *
     * @param $newUsername string The new username
     *
     * @return bool success status
     */
    public static function editUsername($newUsername)
    {
        if ($newUsername === Session::get('user_name')) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_SAME_AS_OLD_ONE'));
            return false;
        }

        // username cannot be empty and must be azAZ09 and 2-64 characters
        if (!preg_match("/^[a-zA-Z0-9]{2,64}$/", $newUsername)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        // clean the input, strip usernames longer than 64 chars (maybe fix this ?)
        $newUsername = substr(strip_tags($newUsername), 0, 64);

        // check if new username already exists
        if (UserMapper::usernameExists($newUsername)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_ALREADY_TAKEN'));
            return false;
        }

        $status_of_action = UserMapper::updateUsername(Session::get('user_id'), $newUsername);
        if (!$status_of_action) {
            Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
            return false;
        }
        Session::set('user_name', $newUsername);
        Session::add('feedback_positive', Text::get('FEEDBACK_USERNAME_CHANGE_SUCCESSFUL'));
        Redirect::myAccount();
        return true;
    }
}
