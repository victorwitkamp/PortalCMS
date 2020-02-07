<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

use PHPMailer\PHPMailer\Exception;
use function strlen;
use PortalCMS\Core\Config\Config;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Recipient\EmailRecipient;
use PortalCMS\Core\Email\SMTP\SMTPConfiguration;
use PortalCMS\Core\Email\SMTP\SMTPTransport;
use PortalCMS\Core\Email\Template\EmailTemplatePDOReader;
use PortalCMS\Core\Email\Template\Helpers\PlaceholderHelper;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

/**
 * Class PasswordReset
 */
class PasswordReset
{
    /**
     * Perform the necessary actions to send a password reset mail
     * @param string $user_name_or_email Username or user's email
     * @return bool success status
     */
    public static function requestPasswordReset(string $user_name_or_email): bool
    {
        if (empty($user_name_or_email)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_EMAIL_FIELD_EMPTY'));
            return false;
        }
        $result = UserPDOReader::getByUsernameOrEmail($user_name_or_email);
        if (empty($result)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USER_DOES_NOT_EXIST'));
            return false;
        }
        $resetToken = sha1(uniqid((string) mt_rand(), true));
        if (self::writeTokenToDatabase($result->user_name, $resetToken, (string) date('Y-m-d H:i:s')) && self::sendPasswordResetMail($result->user_name, $resetToken, $result->user_email)) {
            return true;
        }
        return false;
    }

    /**
     * Set password reset token in database (for DEFAULT user accounts)
     * @param string $user_name username
     * @param string $resetToken password reset hash
     * @param string $timestamp timestamp
     * @return bool success status
     */
    public static function writeTokenToDatabase(string $user_name, string $resetToken, string $timestamp): bool
    {
        $sql = 'UPDATE users
                SET password_reset_hash = :password_reset_hash, user_password_reset_timestamp = :user_password_reset_timestamp
                WHERE user_name = :user_name
                LIMIT 1';
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(
            [
                ':password_reset_hash' => $resetToken, ':user_name' => $user_name,
                ':user_password_reset_timestamp' => $timestamp
            ]
        );
        if ($stmt->rowCount() === 1) {
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_RESET_TOKEN_FAIL'));
        return false;
    }

    /**
     * Send the password reset mail
     * @param string $user_name username
     * @param string $resetToken password reset hash
     * @param string $user_email user email
     * @return bool success status
     */
    public static function sendPasswordResetMail(string $user_name, string $resetToken, string $user_email): bool
    {
        $template = EmailTemplatePDOReader::getSystemTemplateByName('ResetPassword');
        $resetlink = Config::get('URL') .
                        Config::get('EMAIL_PASSWORD_RESET_URL') .
                        '?username=' . $user_name .
                        '&password_reset_hash='
                        .urlencode($resetToken);
        $recipient = new EmailRecipient($user_name, $user_email);
        $SMTPTransport = new SMTPTransport(new SMTPConfiguration());
        if ($SMTPTransport->sendMail(new EmailMessage(
            Config::get('EMAIL_PASSWORD_RESET_SUBJECT'),
            PlaceholderHelper::replace(
                'RESETLINK',
                $resetlink,
                PlaceholderHelper::replace(
                    'SITENAME',
                    SiteSetting::get('site_name'),
                    PlaceholderHelper::replace(
                        'USERNAME',
                        $user_name,
                        $template['body']
                    )
                )
            ),
            [$recipient->get()],
            null
        ))) {
            Session::add('feedback_positive', Text::get('FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL'));
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR') . $SMTPTransport->getError());
        return false;
    }

    /**
     * Verifies the password reset request via the verification hash token (that's only valid for one hour)
     * @param  string $user_name         Username
     * @param  string $verification_code Hash token
     * @return bool Success status
     */
    public static function verifyPasswordReset(string $user_name, string $verification_code): bool
    {
        $sql = 'SELECT user_id, user_password_reset_timestamp
                    FROM users
                        WHERE user_name = :user_name
                            AND password_reset_hash = :password_reset_hash
                                LIMIT 1';
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(
            [
            ':password_reset_hash' => $verification_code,
            ':user_name' => $user_name]
        );
        if ($stmt->rowCount() !== 1) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST'));
            Session::add('feedback_negative', $user_name . ' ' . $sql);
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
     * Writes the new password to the database
     * @param string $user_name           username
     * @param string $passwordHash
     * @param string $resetToken
     * @return bool
     */
    public static function saveNewUserPassword(string $user_name, string $passwordHash, string $resetToken): bool
    {
        $sql = 'UPDATE users SET user_password_hash = :user_password_hash, password_reset_hash = NULL,
                       user_password_reset_timestamp = NULL
                 WHERE user_name = :user_name AND password_reset_hash = :password_reset_hash
                       LIMIT 1';
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(
            [
                ':user_password_hash' => $passwordHash, ':user_name' => $user_name,
                ':password_reset_hash' => $resetToken
            ]
        );
        if ($stmt->rowCount() === 1) {
            return true;
        }
        return false;
    }

    /**
     * Set the new password (for DEFAULT user, FACEBOOK-users don't have a password)
     * Please note: At this point the user has already pre-verified via verifyPasswordReset() (within one hour),
     * so we don't need to check again for the 60min-limit here. In this method we authenticate
     * via username & password-reset-hash from (hidden) form fields.
     * @param string $user_name
     * @param string $resetToken
     * @param string $user_password_new
     * @param string $user_password_repeat
     * @return bool success state of the password reset
     */
    public static function setNewPassword(string $user_name, string $resetToken, string $user_password_new, string $user_password_repeat): bool
    {
        if (self::validateResetPassword($user_name, $resetToken, $user_password_new, $user_password_repeat) && self::saveNewUserPassword($user_name, password_hash(base64_encode($user_password_new), PASSWORD_DEFAULT), $resetToken)) {
            Session::add('feedback_positive', Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL'));
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CHANGE_FAILED'));
        return false;
    }

    /**
     * Validate the password reset submission
     * @param string $user_name user name
     * @param string $resetToken The token required to reset the password
     * @param string $user_password_new The new password of the user
     * @param string $user_password_repeat Confirmation of the new password
     * @return bool Did the password pass validation?
     */
    public static function validateResetPassword(string $user_name, string $resetToken, string $user_password_new, string $user_password_repeat) : bool
    {
        if (empty($user_name)) {
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
}
