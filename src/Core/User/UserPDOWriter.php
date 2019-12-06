<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PortalCMS\Core\Database\DB;

class UserPDOWriter
{
    /**
     * Writes new username to database
     *
     * @param int $user_id user id
     * @param string $newUsername new username
     *
     * @return bool
     */
    public static function updateUsername($user_id, $newUsername): bool
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

    public static function updateFBid($user_id, $fbid): bool
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

    public static function updateRememberMeToken($user_id, $token): bool
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
     * @param string $userId
     * @param string $sessionId
     * @return bool
     */
    public static function updateSessionId($userId, $sessionId = null): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                    SET session_id = :session_id
                    WHERE user_id = :user_id'
        );
        $stmt->execute([':session_id' => $sessionId, ':user_id' => $userId]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * Write timestamp of this login into database (we only write a "real" login via login form into the database,
     * not the session-login on every page request
     *
     * @param $username
     * @return bool
     */
    public static function saveTimestampByUsername($username): bool
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
     *
     * @param $username
     * @return bool
     */
    public static function resetFailedLoginsByUsername($username): bool
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
     *
     * @param $username
     * @return bool
     */
    public static function setFailedLoginByUsername($username): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login
                    WHERE user_name = :user_name
                    OR user_email = :user_name
                    LIMIT 1'
        );
        $stmt->execute([':user_name' => $username, ':user_last_failed_login' => date('Y-m-d H:i:s')]);
        return ($stmt->rowCount() === 1);
    }

    public static function clearRememberMeToken($user_id): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users
                    SET user_remember_me_token = :user_remember_me_token
                    WHERE user_id = :user_id
                    LIMIT 1'
        );
        $stmt->execute([':user_remember_me_token' => null, ':user_id' => $user_id]);
        return ($stmt->rowCount() === 1);
    }

    public static function deleteUser($user_id) : bool
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
