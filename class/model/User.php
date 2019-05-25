<?php

/**
 * User
 * Handles all the PUBLIC profile stuff. This is not for getting data of the logged in user, it's more for handling
 * data of all the other users. Useful for display profile information, creating user lists etc.
 */
class User
{
    /**
     * Gets the user's data
     *
     * @param $user_name string User's name
     *
     * @return mixed Returns false if user does not exist, returns object with user's data when user exists
     */
    public static function getUserByUsername($user_name)
    {

        $stmt = DB::conn()->prepare(
            "SELECT user_id,
                    user_name,
                    user_email,
                    user_password_hash,
                    user_active,
                    user_deleted,
                    user_suspension_timestamp,
                    user_account_type,
                    user_failed_logins,
                    user_last_failed_login,
                    user_fbid
                    FROM users
                        WHERE (user_name = :user_name OR user_email = :user_name)
                        AND user_provider_type = :provider_type
                        LIMIT 1"
        );

        // DEFAULT is the marker for "normal" accounts (that have a password etc.)
        // There are other types of accounts that don't have passwords etc. (FACEBOOK)
        $stmt->execute(array(':user_name' => $user_name, ':provider_type' => 'DEFAULT'));

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Gets the user's data by user's id and a token (used by login-via-cookie process)
     *
     * @param $user_id
     * @param $token
     *
     * @return mixed Returns false if user does not exist, returns object with user's data when user exists
     */
    public static function getUserByUserIdAndToken($user_id, $token)
    {
        $stmt = DB::conn()->prepare(
            "SELECT user_id,
                    user_name,
                    user_email,
                    user_password_hash,
                    user_active,
                    user_account_type,
                    user_has_avatar,
                    user_failed_logins,
                    user_last_failed_login,
                    user_fbid
                    FROM users
                        WHERE user_id = :user_id
                        AND user_remember_me_token = :user_remember_me_token
                        AND user_remember_me_token IS NOT NULL
                        AND user_provider_type = :provider_type
                        LIMIT 1"
        );
        $stmt->execute(
            array(
            ':user_id' => $user_id,
            ':user_remember_me_token' => $token,
            ':provider_type' => 'DEFAULT')
        );
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getUserByFbid($user_fbid)
    {
        $stmt = DB::conn()->prepare(
            "SELECT user_id, user_name, user_email, user_password_hash, user_active,
                    user_account_type, user_has_avatar, user_failed_logins, user_last_failed_login
                    FROM users
                        WHERE user_fbid = :user_fbid
                        AND user_fbid IS NOT NULL
                        LIMIT 1"
        );
        $stmt->execute(array(':user_fbid' => $user_fbid));
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param $user_name_or_email
     *
     * @return mixed
     */
    public static function getUserDataByUserNameOrEmail($user_name_or_email)
    {
        $stmt = DB::conn()->prepare(
            "SELECT user_id, user_name, user_email
                    FROM users
                        WHERE (user_name = :user_name_or_email
                        OR user_email = :user_name_or_email)
                        AND user_provider_type = :provider_type
                        LIMIT 1"
        );
        $stmt->execute(array(':user_name_or_email' => $user_name_or_email, ':provider_type' => 'DEFAULT'));
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Checks if a username is already taken
     *
     * @param $user_name string username
     *
     * @return bool
     */
    public static function doesUsernameExist($user_name)
    {
        $stmt = DB::conn()->prepare(
            "SELECT user_id
                    FROM users
                        WHERE user_name = :user_name
                        LIMIT 1"
        );
        $stmt->execute(
            array(
                ':user_name' => $user_name
            )
        );
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Checks if a email is already used
     *
     * @param $user_email string email
     *
     * @return bool
     */
    public static function doesEmailExist($user_email)
    {
        $stmt = DB::conn()->prepare(
            "SELECT user_id
                    FROM users
                        WHERE user_email = :user_email
                        LIMIT 1"
        );
        $stmt->execute(array(':user_email' => $user_email));
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Writes new username to database
     *
     * @param $user_id int user id
     * @param $new_user_name string new username
     *
     * @return bool
     */
    public static function saveNewUserName($user_id, $new_user_name)
    {
        $stmt = DB::conn()->prepare(
            "UPDATE users
                SET user_name = :user_name
                    WHERE user_id = :user_id
                    LIMIT 1"
        );
        $stmt->execute(
            array(
                ':user_name' => $new_user_name,
                ':user_id' => $user_id
            )
        );
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }

    public static function saveNewFbid($user_id, $fbid)
    {
        $stmt = DB::conn()->prepare(
            "UPDATE users SET user_fbid = ?
            WHERE user_id = ? LIMIT 1"
        );
        $stmt->execute([$fbid, $user_id]);
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }

    public static function clearFbid()
    {
        $user_id = Session::get('user_id');
        $stmt = DB::conn()->prepare(
            "UPDATE users SET user_fbid = NULL
            WHERE user_id = ? LIMIT 1"
        );
        $stmt->execute([$user_id]);
        if ($stmt->rowCount() == 1) {
            Session::set('user_fbid', null);
            $_SESSION['response'][] = array("status" => "success", "message" => 'fbid cleared');
            return true;
        }
        return false;
    }

    /**
     * Edit the user's name, provided in the editing form
     *
     * @param $new_user_name string The new username
     *
     * @return bool success status
     */
    public static function editUserName($new_user_name)
    {
        // Check if new password is indeed different.
        if ($new_user_name == Session::get('user_name')) {
            $_SESSION['response'][] = array("status" => "error", "message" => Text::get('FEEDBACK_USERNAME_SAME_AS_OLD_ONE'));
            return false;
        }

        // username cannot be empty and must be azAZ09 and 2-64 characters
        if (!preg_match("/^[a-zA-Z0-9]{2,64}$/", $new_user_name)) {
            $_SESSION['response'][] = array("status" => "error", "message" => Text::get('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        // clean the input, strip usernames longer than 64 chars (maybe fix this ?)
        $new_user_name = substr(strip_tags($new_user_name), 0, 64);

        // check if new username already exists
        if (self::doesUsernameExist($new_user_name)) {
            $_SESSION['response'][] = array("status" => "error", "message" => Text::get('FEEDBACK_USERNAME_ALREADY_TAKEN'));
            return false;
        }

        $status_of_action = self::saveNewUserName(Session::get('user_id'), $new_user_name);
        if ($status_of_action) {
            Session::set('user_name', $new_user_name);
            $_SESSION['response'][] = array("status" => "success", "message" => Text::get('FEEDBACK_USERNAME_CHANGE_SUCCESSFUL'));
            UserActivity::registerUserActivityByUserId(Session::get('user_id'), 'changeUsername');
            return true;
        } else {
            $_SESSION['response'][] = array("status" => "error", "message" => Text::get('FEEDBACK_UNKNOWN_ERROR'));
            return false;
        }
    }

    /**
     * Gets the user's id
     *
     * @param $user_name
     *
     * @return mixed
     */
    public static function getUserIdByUsername($user_name)
    {
        $sql = "SELECT user_id FROM users WHERE user_name = :user_name AND user_provider_type = :provider_type LIMIT 1";
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(array(':user_name' => $user_name, ':provider_type' => 'DEFAULT'));
        while ($row = $stmt->fetch()) {
            return $row['user_id'];
        }
        return false;
    }

    public static function getProfileById($Id)
    {
        $stmt = DB::conn()->prepare(
            "SELECT user_id,
            user_name,
            session_id,
            user_email,
            user_active,
            user_deleted,
            user_account_type,
            user_failed_logins,
            user_last_login_timestamp,
            user_failed_logins,
            user_last_failed_login,
            user_provider_type,
            user_fbid,
            CreationDate,
            ModificationDate
                    FROM users
                        WHERE user_id = :user_id
                        AND user_id IS NOT NULL
                        LIMIT 1"
        );
        $stmt->execute(array(':user_id' => $Id));
        if (!$stmt->rowCount() > 0) {
            return false;
        } else {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    static function getRoles($Id)
    {
        $stmt = DB::conn()->prepare("SELECT role_id FROM user_role where user_id = ? ORDER BY role_id");
        $stmt->execute([$Id]);
        return $stmt->fetchAll();
    }

    /**
     * Increments the failed-login counter of a user
     *
     * @param $user_name
     */
    public static function incrementFailedLoginCounterOfUser($user_name)
    {
        $sql = "UPDATE users
                SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login
                WHERE user_name = :user_name OR user_email = :user_name
                LIMIT 1";
        $sth = DB::conn()->prepare($sql);
        $sth->execute(array(':user_name' => $user_name, ':user_last_failed_login' => date('Y-m-d H:i:s')));
    }

    /**
     * Resets the failed-login counter of a user back to 0
     *
     * @param $user_name
     */
    public static function resetFailedLoginCounterOfUser($user_name)
    {
        $sql = "UPDATE users
                SET user_failed_logins = 0, user_last_failed_login = NULL
                WHERE user_name = :user_name AND user_failed_logins != 0
                LIMIT 1";
        $sth = DB::conn()->prepare($sql);
        $sth->execute(array(':user_name' => $user_name));
    }


    /**
     * Write timestamp of this login into database (we only write a "real" login via login form into the database,
     * not the session-login on every page request
     *
     * @param $user_name
     */
    public static function saveTimestampOfLoginOfUser($user_name)
    {
        $sql = "UPDATE users
                SET user_last_login_timestamp = :user_last_login_timestamp
                WHERE user_name = :user_name LIMIT 1";
        $sth = DB::conn()->prepare($sql);
        $sth->execute(array(':user_name' => $user_name, ':user_last_login_timestamp' => date('Y-m-d H:i:s')));
    }
}
