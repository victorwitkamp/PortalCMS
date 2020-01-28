<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\User;

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
use PortalCMS\Core\User\UserPDOReader;
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
    public static function requestPasswordReset($user_name_or_email): bool
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
        $timestamp = date('Y-m-d H:i:s');
        $password_reset_hash = sha1(uniqid((string) mt_rand(), true));
        $token_set = self::writeTokenToDatabase(
            $result->user_name,
            $password_reset_hash,
            $timestamp
        );
        if (!$token_set) {
            return false;
        }
        $mail_sent = self::sendPasswordResetMail(
            $result->user_name,
            $password_reset_hash,
            $result->user_email
        );
        if ($mail_sent) {
            return true;
        }
        return false;
    }

    /**
     * Set password reset token in database (for DEFAULT user accounts)
     * @param string $user_name           username
     * @param string $password_reset_hash password reset hash
     * @param int    $timestamp           timestamp
     * @return bool success status
     */
    public static function writeTokenToDatabase($user_name, $password_reset_hash, $timestamp): bool
    {
        $sql = 'UPDATE users
                SET password_reset_hash = :password_reset_hash, user_password_reset_timestamp = :user_password_reset_timestamp
                WHERE user_name = :user_name
                LIMIT 1';
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(
            [
                ':password_reset_hash' => $password_reset_hash, ':user_name' => $user_name,
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
     * @param string $password_reset_hash password reset hash
     * @param string $user_email user email
     * @return bool success status
     */
    public static function sendPasswordResetMail($user_name, $password_reset_hash, $user_email): bool
    {
        $Mail = EmailTemplatePDOReader::getSystemTemplateByName('ResetPassword');
        $MailText = $Mail['body'];
        $resetlink = Config::get('URL') .
                        Config::get('EMAIL_PASSWORD_RESET_URL') .
                        '?username=' . $user_name .
                        '&password_reset_hash='
                        .urlencode($password_reset_hash);
        $MailText = PlaceholderHelper::replace('USERNAME', $user_name, $MailText);
        $MailText = PlaceholderHelper::replace('SITENAME', SiteSetting::getStaticSiteSetting('site_name'), $MailText);
        $MailText = PlaceholderHelper::replace('RESETLINK', $resetlink, $MailText);
        $EmailRecipient = new EmailRecipient($user_name, $user_email);
        $mail = new EmailMessage(
            Config::get('EMAIL_PASSWORD_RESET_SUBJECT'),
            $MailText,
            [$EmailRecipient->get()],
            null
        );
        $SMTPConfiguration = new SMTPConfiguration();
        $SMTPTransport = new SMTPTransport($SMTPConfiguration);
        if ($SMTPTransport->sendMail($mail)) {
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
    public static function verifyPasswordReset($user_name, $verification_code): bool
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
        $timestamp_one_hour_ago = strtotime(date('Y-m-d H:i:s')) - 3600;
        $user_timestamp = strtotime($result_user_row['user_password_reset_timestamp']);
        if ($user_timestamp > $timestamp_one_hour_ago) {
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_RESET_LINK_EXPIRED'));
        return false;
    }

    /**
     * Writes the new password to the database
     * @param string $user_name           username
     * @param string $user_password_hash
     * @param string $password_reset_hash
     * @return bool
     */
    public static function saveNewUserPassword($user_name, $user_password_hash, $password_reset_hash): bool
    {
        $sql = 'UPDATE users SET user_password_hash = :user_password_hash, password_reset_hash = NULL,
                       user_password_reset_timestamp = NULL
                 WHERE user_name = :user_name AND password_reset_hash = :password_reset_hash
                       LIMIT 1';
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(
            [
                ':user_password_hash' => $user_password_hash, ':user_name' => $user_name,
                ':password_reset_hash' => $password_reset_hash
            ]
        );
        if ($stmt->rowCount() === 1) {
            Session::add('feedback_positive', 'Wachtwoord gewijzigd.');
            return true;
        }
        Session::add('feedback_negative', 'Wachtwoord niet gewijzigd.');
        return false;
    }

    /**
     * Set the new password (for DEFAULT user, FACEBOOK-users don't have a password)
     * Please note: At this point the user has already pre-verified via verifyPasswordReset() (within one hour),
     * so we don't need to check again for the 60min-limit here. In this method we authenticate
     * via username & password-reset-hash from (hidden) form fields.
     * @param string $user_name
     * @param string $password_reset_hash
     * @param string $user_password_new
     * @param string $user_password_repeat
     * @return bool success state of the password reset
     */
    public static function setNewPassword($user_name, $password_reset_hash, $user_password_new, $user_password_repeat): bool
    {
        if (!self::validateResetPassword($user_name, $password_reset_hash, $user_password_new, $user_password_repeat)) {
            return false;
        }
        $user_password_hash = password_hash(
            base64_encode(
                $user_password_new
            ),
            PASSWORD_DEFAULT
        );
        if (self::saveNewUserPassword($user_name, $user_password_hash, $password_reset_hash)) {
            Session::add('feedback_positive', Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL'));
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_CHANGE_FAILED'));
        return false;
    }

    /**
     * Validate the password submission
     * @param $user_name
     * @param $password_reset_hash
     * @param $user_password_new
     * @param $user_password_repeat
     * @return bool
     */
    public static function validateResetPassword($user_name, $password_reset_hash, $user_password_new, $user_password_repeat)
    {
        if (empty($user_name)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_FIELD_EMPTY'));
            return false;
        }
        if (empty($password_reset_hash)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_RESET_TOKEN_MISSING'));
            return false;
        }
        if (empty($user_password_new) || empty($user_password_repeat)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY'));
            return false;
        }
        if ($user_password_new !== $user_password_repeat) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG'));
            return false;
        }
        if (strlen($user_password_new) < 6) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_TOO_SHORT'));
            return false;
        }
        return true;
    }
}
