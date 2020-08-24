<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;
use function strlen;

/**
 * Class Password
 * @package PortalCMS\Core\User
 */
class Password
{
    public static function changePassword(object $user, string $currentPassword, string $newPassword, string $repeatNewPassword): bool
    {
        if (empty($currentPassword) || empty($newPassword) || empty($repeatNewPassword)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY'));
        } elseif ($newPassword !== $repeatNewPassword) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG'));
        } elseif (self::verifyPassword($user, $currentPassword) === false) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CURRENT_INCORRECT'));
        } elseif (self::validatePasswordChange($currentPassword, $newPassword) && UserMapper::updatePassword($user->user_name, password_hash(base64_encode($newPassword), PASSWORD_DEFAULT))) {
            Session::add('feedback_positive', Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL'));
            return true;
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CHANGE_FAILED'));
        }
        return false;
    }

    public static function verifyPassword(object $user, string $user_password): bool
    {
        return (password_verify(base64_encode($user_password), $user->user_password_hash));
    }

    public static function validatePasswordChange(string $currentPassword, string $newPassword): bool
    {
        if ($currentPassword === $newPassword) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_NEW_SAME_AS_CURRENT'));
        } elseif (strlen($newPassword) <= 6) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_TOO_SHORT'));
        } else {
            return true;
        }
        return false;
    }
}
