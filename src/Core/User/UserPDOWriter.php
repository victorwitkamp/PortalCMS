<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PortalCMS\Core\Database\DB;

class UserPDOWriter
{
    /**
     * @param int $user_id user id
     * @param string $newUsername new username
     * @return bool Was the username updated succesfully?
     */
    public static function updateUsername(int $user_id, string $newUsername): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                SET user_name = :user_name
                    WHERE user_id = :user_id
                        LIMIT 1'
        );
        $stmt->execute([':user_name' => $newUsername, ':user_id' => $user_id]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int $user_id user id
     * @param int $fbid facebook id
     * @return bool Was the FBid updated succesfully?
     */
    public static function updateFBid(int $user_id, int $fbid = null): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                SET user_fbid = ?
                    WHERE user_id = ?
                        LIMIT 1'
        );
        $stmt->execute([$fbid, $user_id]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int $user_id user id
     * @param string $token remember me token
     * @return bool Was the token updated succesfully?
     */
    public static function updateRememberMeToken(int $user_id, string $token): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                    SET user_remember_me_token = ?
                        WHERE user_id = ?
                            LIMIT 1'
        );
        $stmt->execute([$token, $user_id]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int $userId user id
     * @param string $sessionId session id
     * @return bool Was the session id updated succesfully?
     */
    public static function updateSessionId(int $userId, string $sessionId = null): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                    SET session_id = :session_id
                        WHERE user_id = :user_id
                            LIMIT 1'
        );
        $stmt->execute([':session_id' => $sessionId, ':user_id' => $userId]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * Write timestamp of this login into database (we only write a "real" login via login form into the database,
     * not the session-login on every page request
     * @param $username
     * @return bool Was the last login timestamp updated succesfully?
     */
    public static function saveTimestampByUsername(string $username): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                SET user_last_login_timestamp = ?
                    WHERE user_name = ?
                        LIMIT 1'
        );
        $stmt->execute([date('Y-m-d H:i:s'), $username]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * Resets the failed-login counter of a user back to 0
     * @param string $username user name
     * @return bool Was the failed login counter set to 0 succesfully?
     */
    public static function resetFailedLoginsByUsername(string $username): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                SET user_failed_logins = 0, user_last_failed_login = NULL
                    WHERE user_name = ?
                        AND user_failed_logins != 0
                            LIMIT 1'
        );
        $stmt->execute([$username]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * Increments the failed-login counter of a user
     * @param string $username user name
     * @return bool Was the failed login counter incremented succesfully?
     */
    public static function setFailedLoginByUsername(string $username): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login
                    WHERE user_name = :user_name
                        OR user_email = :user_email
                            LIMIT 1'
        );
        $stmt->execute([':user_name' => $username, ':user_email' => $username, ':user_last_failed_login' => date('Y-m-d H:i:s')]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int $user_id user id
     * @return bool Was the remember me token cleared successfully?
     */
    public static function clearRememberMeToken(int $user_id): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                    SET user_remember_me_token = NULL
                        WHERE user_id = ?
                            LIMIT 1'
        );
        $stmt->execute([$user_id]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int $user_id user id
     * @return bool Was the user deleted successfully?
     */
    public static function deleteUser(int $user_id) : bool
    {
        $stmt = DB::conn()->prepare(
            'DELETE FROM users
                WHERE user_id = ?
                    LIMIT 1'
        );
        $stmt->execute([$user_id]);
        return ($stmt->rowCount() === 1);
    }
}
