<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use function strlen;
use PDO;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

/**
 * Class Password
 */
class Password
{
    /**
     * Writes the new password to the database
     * @param string $user_name
     * @param string $user_password_hash
     * @return bool
     */
    public static function saveChangedPassword(string $user_name, string $user_password_hash): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users SET user_password_hash = :user_password_hash
                    WHERE user_name = :user_name
                        LIMIT 1'
        );
        $stmt->execute(
            [
                ':user_password_hash' => $user_password_hash,
                ':user_name' => $user_name
            ]
        );
        return ($stmt->rowCount() === 1);
    }

    /**
     * Validates fields, hashes new password, saves new password
     * @param $user_name
     * @param $currentPassword
     * @param $newPassword
     * @param $repeatNewPassword
     * @return bool Was the password changed successfully?
     */
    public static function changePassword(string $user_name, string $currentPassword, string $newPassword, string $repeatNewPassword): bool
    {
        if (self::validatePasswordChange($user_name, $currentPassword, $newPassword, $repeatNewPassword) && self::saveChangedPassword($user_name, password_hash(base64_encode($newPassword), PASSWORD_DEFAULT))) {
            Session::add('feedback_positive', Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL'));
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CHANGE_FAILED'));
        return false;
    }

    /**
     * Validates current and new password
     * @param $user_name
     * @param $currentPassword
     * @param $newPassword
     * @param $repeatNewPassword
     * @return bool Did the password pass validation?
     */
    public static function validatePasswordChange(string $user_name, string $currentPassword, string $newPassword, string $repeatNewPassword): bool
    {
        $stmt = DB::conn()->prepare('SELECT user_password_hash, user_failed_logins FROM users WHERE user_name = :user_name LIMIT 1;');
        $stmt->execute([':user_name' => $user_name]);
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            if (!password_verify(base64_encode($currentPassword), $user->user_password_hash)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CURRENT_INCORRECT'));
            } elseif (empty($newPassword) || empty($repeatNewPassword)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY'));
            } elseif ($newPassword !== $repeatNewPassword) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG'));
            } elseif (strlen($newPassword) <= 6) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_TOO_SHORT'));
            } elseif ($currentPassword === $newPassword) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_NEW_SAME_AS_CURRENT'));
            } else {
                return true;
            }
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_USER_DOES_NOT_EXIST'));
        }
        return false;
    }
}
