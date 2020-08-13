<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PortalCMS\Core\Database\Database;

/**
 * Class Registration
 * @package PortalCMS\Core\User
 */
class Registration
{
    /**
     * @param int $userID
     */
    public static function rollbackRegistrationByUserId(int $userID)
    {
        $stmt = Database::conn()->prepare('DELETE FROM users WHERE user_id = ?');
        $stmt->execute([ $userID ]);
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $md5password
     * @param string $activationCode
     * @return bool
     */
    public static function writeNewUserToDatabase(string $username, string $email, string $md5password, string $activationCode): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO users (user_name, user_email, password, confirm_code) VALUES (?, ?, ?, ?)');
        $stmt->execute([ $username, $email, $md5password, $activationCode ]);
        return ($stmt->rowCount() === 1);
    }
}
