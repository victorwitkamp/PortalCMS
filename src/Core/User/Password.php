<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use function strlen;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

class Password
{
    public static function saveChangedPassword(string $user_name, string $user_password_hash): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE users 
                        SET user_password_hash = :user_password_hash
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

    public static function validatePasswordChange(string $username, string $currentPassword, string $newPassword, string $repeatNewPassword): bool
    {
        $user = UserPDOReader::getByUsername($username);
        if (!empty($user)) {
            if (empty($currentPassword) || empty($newPassword) || empty($repeatNewPassword)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY'));
            } elseif (!self::verifyPassword($user, $currentPassword)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CURRENT_INCORRECT'));
            } elseif ($currentPassword === $newPassword) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_NEW_SAME_AS_CURRENT'));
            } elseif ($newPassword !== $repeatNewPassword) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG'));
            } elseif (strlen($newPassword) <= 6) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_TOO_SHORT'));
            } else {
                return true;
            }
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_USER_DOES_NOT_EXIST'));
        }
        return false;
    }

    public static function verifyPassword(object $user, string $user_password) : bool
    {
        if (!password_verify(base64_encode($user_password), $user->user_password_hash)) {
            return false;
        }
        return true;
    }
}
