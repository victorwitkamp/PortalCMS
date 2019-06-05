<?php

/**
 * Class Password
 *
 * Handles all the stuff that is related to the password
 */
class Password
{
    /**
     * Writes the new password to the database
     *
     * @param string $user_name
     * @param string $user_password_hash
     *
     * @return bool
     */
    public static function saveChangedPassword($user_name, $user_password_hash)
    {
        $sql = "UPDATE users SET user_password_hash = :user_password_hash
                 WHERE user_name = :user_name
                 AND user_provider_type = :user_provider_type LIMIT 1";
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(array(':user_password_hash' => $user_password_hash, ':user_name' => $user_name, ':user_provider_type' => 'DEFAULT'));
        return ($stmt->rowCount() == 1 ? true : FALSE);
    }

    /**
     * Validates fields, hashes new password, saves new password
     *
     * @param string $user_name
     * @param string $user_password_current
     * @param string $user_password_new
     * @param string $user_password_repeat
     *
     * @return bool
     */
    public static function changePassword($user_name, $user_password_current, $user_password_new, $user_password_repeat)
    {
        // validate the passwords
        if (!self::validatePasswordChange($user_name, $user_password_current, $user_password_new, $user_password_repeat)) {
            return FALSE;
        }
        // crypt the password (with the PHP 5.5+'s password_hash() function, result is a 60 character hash string)
        $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);
        // write the password to database (as hashed and salted string)
        if (self::saveChangedPassword($user_name, $user_password_hash)) {
            Session::add('feedback_positive', Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL'));
            return TRUE;
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CHANGE_FAILED'));
            return FALSE;
        }
    }


    /**
     * Validates current and new passwords
     *
     * @param string $user_name
     * @param string $user_password_current
     * @param string $user_password_new
     * @param string $user_password_repeat
     *
     * @return bool
     */
    public static function validatePasswordChange($user_name, $user_password_current, $user_password_new, $user_password_repeat)
    {
        $stmt = DB::conn()->prepare("SELECT user_password_hash, user_failed_logins FROM users WHERE user_name = :user_name LIMIT 1;");
        $stmt->execute(array(':user_name' => $user_name));
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if ($stmt->rowCount() == 1) {
            $user_password_hash = $user->user_password_hash;
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_USER_DOES_NOT_EXIST'));
            return FALSE;
        }
        if (!password_verify($user_password_current, $user_password_hash)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CURRENT_INCORRECT'));
            return FALSE;
        } else if (empty($user_password_new) || empty($user_password_repeat)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY'));
            return FALSE;
        } else if ($user_password_new !== $user_password_repeat) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG'));
            return FALSE;
        } else if (strlen($user_password_new) < 6) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_TOO_SHORT'));
            return FALSE;
        } else if ($user_password_current == $user_password_new) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_NEW_SAME_AS_CURRENT'));
            return FALSE;
        }
        return TRUE;
    }
}
