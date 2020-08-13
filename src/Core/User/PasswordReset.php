<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Database\Database;
use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Recipient\EmailRecipient;
use PortalCMS\Core\Email\SMTP\SMTPConfiguration;
use PortalCMS\Core\Email\SMTP\SMTPTransport;
use PortalCMS\Core\Email\Template\EmailTemplateMapper;
use PortalCMS\Core\Email\Template\Helpers\PlaceholderHelper;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;
use function strlen;

/**
 * Class PasswordReset
 * @package PortalCMS\Core\User
 */
class PasswordReset
{
    /**
     * @param string $username_or_email
     * @return bool
     */
    public static function requestPasswordReset(string $username_or_email): bool
    {
        if (empty($username_or_email)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_EMAIL_FIELD_EMPTY'));
            return false;
        }
        $result = UserMapper::getByUsernameOrEmail($username_or_email);
        if (empty($result)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USER_DOES_NOT_EXIST'));
            return false;
        }
        $resetToken = sha1(uniqid((string)mt_rand(), true));
        return self::writeTokenToDatabase($result->user_name, $resetToken, (string)date('Y-m-d H:i:s')) && self::sendPasswordResetMail($result->user_name, $resetToken, $result->user_email);
    }

    /**
     * @param string $username
     * @param string $resetToken
     * @param string $timestamp
     * @return bool
     */
    public static function writeTokenToDatabase(string $username, string $resetToken, string $timestamp): bool
    {
        $sql = 'UPDATE users
                SET password_reset_hash = :password_reset_hash, user_password_reset_timestamp = :user_password_reset_timestamp
                WHERE user_name = :user_name
                LIMIT 1';
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
                ':password_reset_hash' => $resetToken, ':user_name' => $username, ':user_password_reset_timestamp' => $timestamp
            ]);
        if ($stmt->rowCount() === 1) {
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_RESET_TOKEN_FAIL'));
        return false;
    }

    /**
     * @param string $username
     * @param string $resetToken
     * @param string $user_email
     * @return bool
     */
    public static function sendPasswordResetMail(string $username, string $resetToken, string $user_email): bool
    {
        $template = EmailTemplateMapper::getSystemTemplateByName('ResetPassword');
        $resetlink = Config::get('URL') . Config::get('EMAIL_PASSWORD_RESET_URL') . '?username=' . $username . '&password_reset_hash=' . urlencode($resetToken);
        $recipient = new EmailRecipient($username, $user_email);
        $SMTPTransport = new SMTPTransport(new SMTPConfiguration());
        if ($SMTPTransport->sendMail(new EmailMessage(Config::get('EMAIL_PASSWORD_RESET_SUBJECT'), PlaceholderHelper::replace('RESETLINK', $resetlink, PlaceholderHelper::replace('SITENAME', SiteSetting::get('site_name'), PlaceholderHelper::replace('USERNAME', $username, $template['body']))), [ $recipient->get() ], null))) {
            Session::add('feedback_positive', Text::get('FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL'));
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR') . $SMTPTransport->getError());
        return false;
    }

    /**
     * @param string $username
     * @param string $password_reset_hash
     * @return bool
     */
    public static function verifyPasswordReset(string $username, string $password_reset_hash): bool
    {
        $sql = 'SELECT user_id, user_password_reset_timestamp
                    FROM users
                        WHERE user_name = :user_name
                            AND password_reset_hash = :password_reset_hash
                                LIMIT 1';
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
                ':password_reset_hash' => $password_reset_hash, ':user_name' => $username
            ]);
        if ($stmt->rowCount() !== 1) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST'));
            Session::add('feedback_negative', $username . ' ' . $sql);
            return false;
        }
        $result_user_row = $stmt->fetch();
        $oneHourAgo = strtotime(date('Y-m-d H:i:s')) - 3600;
        $user_timestamp = strtotime($result_user_row['user_password_reset_timestamp']);
        if ($user_timestamp > $oneHourAgo) {
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_RESET_LINK_EXPIRED'));
        return false;
    }

    /**
     * Please note: At this point the user has already pre-verified via verifyPasswordReset() (within one hour),
     * so we don't need to check again for the 60min-limit here. In this method we authenticate
     * via username & password-reset-hash from (hidden) form fields.
     * @param string $username
     * @param string $resetToken
     * @param string $user_password_new
     * @param string $user_password_repeat
     * @return bool
     */
    public static function setNewPassword(string $username, string $resetToken, string $user_password_new, string $user_password_repeat): bool
    {
        if (self::validateResetPassword($username, $resetToken, $user_password_new, $user_password_repeat) && self::saveNewUserPassword($username, password_hash(base64_encode($user_password_new), PASSWORD_DEFAULT), $resetToken)) {
            Session::add('feedback_positive', Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL'));
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CHANGE_FAILED'));
        return false;
    }

    /**
     * @param string $username
     * @param string $resetToken
     * @param string $user_password_new
     * @param string $user_password_repeat
     * @return bool
     */
    public static function validateResetPassword(string $username, string $resetToken, string $user_password_new, string $user_password_repeat): bool
    {
        if (empty($username)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_FIELD_EMPTY'));
        } elseif (empty($resetToken)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_RESET_TOKEN_MISSING'));
        } elseif (empty($user_password_new) || empty($user_password_repeat)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY'));
        } elseif ($user_password_new !== $user_password_repeat) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG'));
        } elseif (strlen($user_password_new) < 6) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_TOO_SHORT'));
        } else {
            return true;
        }
        return false;
    }

    /**
     * @param string $username
     * @param string $passwordHash
     * @param string $resetToken
     * @return bool
     */
    public static function saveNewUserPassword(string $username, string $passwordHash, string $resetToken): bool
    {
        $sql = 'UPDATE users SET user_password_hash = :user_password_hash, password_reset_hash = NULL,
                       user_password_reset_timestamp = NULL
                 WHERE user_name = :user_name AND password_reset_hash = :password_reset_hash
                       LIMIT 1';
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
                ':user_password_hash' => $passwordHash, ':user_name' => $username, ':password_reset_hash' => $resetToken
            ]);
        return ($stmt->rowCount() === 1);
    }
}
