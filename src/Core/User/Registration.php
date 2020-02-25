<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PortalCMS\Core\Database\DB;

class Registration
{
    public static function rollbackRegistrationByUserId(int $userID)
    {
        $stmt = DB::conn()->prepare('DELETE FROM users WHERE user_id = ?');
        $stmt->execute([$userID]);
    }

    public static function writeNewUserToDatabase(string $username, string $email, string $md5password, string $activationCode): bool
    {
        $stmt = DB::conn()->prepare('INSERT INTO users (user_name, user_email, password, confirm_code) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $email, $md5password, $activationCode]);
        return ($stmt->rowCount() === 1);
    }
}
