<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PDO;
use PortalCMS\Core\Database\Database;

/**
 * Class UserMapper
 * @package PortalCMS\Core\User
 */
class UserMapper
{
    public static function usernameExists(string $user_name): bool
    {
        $stmt = Database::conn()->prepare('SELECT user_id
                    FROM users
                        WHERE user_name = ?
                        LIMIT 1');
        $stmt->execute([$user_name]);
        return ($stmt->rowCount() === 1);
    }

    public static function getProfileById(int $Id): ?object
    {
        $stmt = Database::conn()->prepare('SELECT user_id,
            user_name,
            session_id,
            user_email,
            user_active,
            user_deleted,
            user_account_type,
            user_failed_logins,
            user_last_login_timestamp,
            user_last_failed_login,
            user_provider_type,
            user_fbid,
            CreationDate,
            ModificationDate
                    FROM users
                        WHERE user_id = :user_id
                        AND user_id IS NOT NULL
                        LIMIT 1');
        $stmt->execute([':user_id' => $Id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    /**
     */
    public static function getByUsername(string $username): ?object
    {
        $stmt = Database::conn()->prepare('SELECT user_id,
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
                                LIMIT 1');
        $stmt->execute([$username, $username]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getByIdAndToken(int $user_id, string $token): ?object
    {
        $stmt = Database::conn()->prepare('SELECT user_id,
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
                                LIMIT 1');
        $stmt->execute([
                ':user_id' => $user_id, ':user_remember_me_token' => $token
            ]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getByFbid(int $user_fbid): ?object
    {
        $stmt = Database::conn()->prepare('SELECT *
                            FROM users
                                WHERE user_fbid = :user_fbid
                                    AND user_fbid IS NOT NULL
                                        LIMIT 1');
        $stmt->execute([':user_fbid' => $user_fbid]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getByUsernameOrEmail(string $usernameOrEmail): ?object
    {
        $stmt = Database::conn()->prepare('SELECT user_id, user_name, user_email
                    FROM users
                        WHERE user_name = ?
                            OR user_email = ?
                                LIMIT 1');
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getUsers(): ?array
    {
        $stmt = Database::conn()->query('SELECT * FROM users ORDER BY user_id ');
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function updatePassword(string $username, string $user_password_hash): bool
    {
        $stmt = Database::conn()->prepare('UPDATE users
                        SET user_password_hash = :user_password_hash
                            WHERE user_name = :user_name
                                LIMIT 1');
        $stmt->execute([
                ':user_password_hash' => $user_password_hash, ':user_name' => $username
            ]);
        return ($stmt->rowCount() === 1);
    }

    public static function updateUsername(int $user_id, string $newUsername): bool
    {
        $stmt = Database::conn()->prepare('UPDATE users
                SET user_name = :user_name
                    WHERE user_id = :user_id
                        LIMIT 1');
        $stmt->execute([':user_name' => $newUsername, ':user_id' => $user_id]);
        return ($stmt->rowCount() === 1);
    }

    public static function updateFBid(int $user_id, int $fbid = null): bool
    {
        $stmt = Database::conn()->prepare('UPDATE users
                SET user_fbid = ?
                    WHERE user_id = ?
                        LIMIT 1');
        $stmt->execute([$fbid, $user_id]);
        return ($stmt->rowCount() === 1);
    }

    public static function updateRememberMeToken(int $user_id, string $token): bool
    {
        $stmt = Database::conn()->prepare('UPDATE users
                    SET user_remember_me_token = ?
                        WHERE user_id = ?
                            LIMIT 1');
        $stmt->execute([$token, $user_id]);
        return ($stmt->rowCount() === 1);
    }

    public static function updateSessionId(int $userId, string $sessionId = null): bool
    {
        $stmt = Database::conn()->prepare('UPDATE users
                    SET session_id = :session_id
                        WHERE user_id = :user_id
                            LIMIT 1');
        $stmt->execute([':session_id' => $sessionId, ':user_id' => $userId]);
        return ($stmt->rowCount() === 1);
    }

    public static function saveTimestampByUsername(string $username): bool
    {
        $stmt = Database::conn()->prepare('UPDATE users
                SET user_last_login_timestamp = ?
                    WHERE user_name = ?
                        LIMIT 1');
        $stmt->execute([date('Y-m-d H:i:s'), $username]);
        return ($stmt->rowCount() === 1);
    }

    public static function resetFailedLoginsByUsername(string $username): bool
    {
        $stmt = Database::conn()->prepare('UPDATE users
                SET user_failed_logins = 0, user_last_failed_login = NULL
                    WHERE user_name = ?
                        AND user_failed_logins != 0
                            LIMIT 1');
        $stmt->execute([$username]);
        return ($stmt->rowCount() === 1);
    }

    public static function setFailedLoginByUsername(string $username): bool
    {
        $stmt = Database::conn()->prepare('UPDATE users
                SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login
                    WHERE user_name = :user_name
                        OR user_email = :user_email
                            LIMIT 1');
        $stmt->execute([':user_name' => $username, ':user_email' => $username, ':user_last_failed_login' => date('Y-m-d H:i:s')]);
        return ($stmt->rowCount() === 1);
    }

    public static function clearRememberMeToken(int $user_id): bool
    {
        $stmt = Database::conn()->prepare('UPDATE users
                    SET user_remember_me_token = NULL
                        WHERE user_id = ?
                            LIMIT 1');
        $stmt->execute([$user_id]);
        return ($stmt->rowCount() === 1);
    }

    public static function deleteUser(int $user_id): bool
    {
        $stmt = Database::conn()->prepare('DELETE FROM users
                WHERE user_id = ?
                    LIMIT 1');
        $stmt->execute([$user_id]);
        return ($stmt->rowCount() === 1);
    }
}
