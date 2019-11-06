<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PortalCMS\Core\Database\DB;

/**
 * Class Registration
 *
 * Everything registration-related happens here.
 */
class Registration
{
    /**
     * Deletes the user from users table. Currently used to rollback a registration when verification mail sending
     * was not successful.
     *
     * @param $userID
     */
    public static function rollbackRegistrationByUserId($userID)
    {
        $stmt = DB::conn()->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$userID]);
    }


    /**
     * Writes the new user's data to the database
     *
     * @param $username
     * @param $email
     * @param $md5password
     * @param $activationCode
     *
     * @return bool
     */
    public static function writeNewUserToDatabase($username, $email, $md5password, $activationCode)
    {
        $stmt = DB::conn()->prepare('INSERT INTO users (username, email, password, confirm_code) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $email, $md5password, $activationCode]);
        return $stmt->rowCount() === 1;
    }
}
