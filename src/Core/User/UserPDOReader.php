<?php
declare(strict_types=1);

namespace PortalCMS\Core\User;

use PDO;
use PortalCMS\Core\Database\DB;

class UserPDOReader
{
    /**
     * Checks if a username is already taken
     *
     * @param $user_name
     * @return bool
     */
    public static function usernameExists($user_name): bool
    {
        $stmt = DB::conn()->prepare(
            'SELECT user_id
                    FROM users
                        WHERE user_name = ?
                        LIMIT 1'
        );
        $stmt->execute([$user_name]);
        return ($stmt->rowCount() === 1);
    }

    public static function getProfileById(int $Id) : ?object
    {
        $stmt = DB::conn()->prepare(
            'SELECT user_id,
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
                        LIMIT 1'
        );
        $stmt->execute([':user_id' => $Id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    /**
     * @param string $username User's name
     * @return object|null
     */
    public static function getByUsername(string $username) : ?object
    {
        $stmt = DB::conn()->prepare(
            'SELECT user_id,
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
                        WHERE (user_name = ? OR user_email = ?)
                            AND user_provider_type = "DEFAULT"
                                LIMIT 1'
        );
        $stmt->execute([$username, $username]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Gets the user's data by user's id and a token (used by login-via-cookie process)
     * @param int $user_id
     * @param string $token
     * @return mixed Returns false if user does not exist, returns object with user's data when user exists
     */
    public static function getByIdAndToken(int $user_id, string $token) : ?object
    {
        $stmt = DB::conn()->prepare(
            'SELECT user_id,
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
                                LIMIT 1'
        );
        $stmt->execute(
            [
                ':user_id' => $user_id,
                ':user_remember_me_token' => $token,
                ':provider_type' => 'DEFAULT'
            ]
        );
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param int $user_fbid
     * @return object|null
     */
    public static function getByFbid(int $user_fbid) : ?object
    {
        $stmt = DB::conn()->prepare(
            // 'SELECT user_id, user_name, user_email, user_password_hash, user_active,
            //         user_account_type, user_has_avatar, user_failed_logins, user_last_failed_login
                'SELECT *
                    FROM users
                        WHERE user_fbid = :user_fbid
                            AND user_fbid IS NOT NULL
                                LIMIT 1'
        );
        $stmt->execute([':user_fbid' => $user_fbid]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param string $usernameOrEmail
     * @return object|null
     */
    public static function getByUsernameOrEmail(string $usernameOrEmail) : ?object
    {
        $stmt = DB::conn()->prepare(
            'SELECT user_id, user_name, user_email
                    FROM users
                        WHERE (user_name = :user_name_or_email
                        OR user_email = :user_name_or_email)
                        AND user_provider_type = :provider_type
                        LIMIT 1'
        );
        $stmt->execute([':user_name_or_email' => $usernameOrEmail, ':provider_type' => 'DEFAULT']);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
