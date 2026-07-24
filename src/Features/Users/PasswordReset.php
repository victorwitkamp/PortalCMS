<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users;

use DateTimeImmutable;
use PortalCMS\Core\Config\Config;
use PortalCMS\Features\Email\Message\EmailMessage;
use PortalCMS\Features\Email\Recipient\EmailRecipient;
use PortalCMS\Features\Email\Template\MailTemplate;
use PortalCMS\Features\Email\Transport\MailTransport;
use PortalCMS\Features\Settings\SiteSetting;
use PortalCMS\Features\Users\Entity\User;
use PortalCMS\Features\Users\Repository\UserRepository;

final class PasswordReset
{
    private ?string $error = null;

    public function __construct(
        private readonly UserRepository $users,
        private readonly MailTemplate $templates,
        private readonly MailTransport $transport,
        private readonly SiteSetting $settings,
    ) {
    }

    public function request(string $usernameOrEmail): bool
    {
        $this->error = null;
        if ($usernameOrEmail === '') {
            $this->error = 'Voer een gebruikersnaam of e-mailadres in.';
            return false;
        }

        $user = $this->users->findByUsernameOrEmail($usernameOrEmail);
        if (!$user instanceof User) {
            $this->error = 'Gebruiker bestaat niet.';
            return false;
        }
        $template = $this->templates->system('ResetPassword');
        if ($template === null || empty($template->body)) {
            $this->error = 'De ResetPassword-template bestaat niet.';
            return false;
        }

        $token = bin2hex(random_bytes(20));
        $user->setPasswordResetToken($token, new DateTimeImmutable());
        $this->users->flush();

        $resetLink = rtrim((string) Config::get('URL'), '/')
            . '/' . ltrim((string) Config::get('EMAIL_PASSWORD_RESET_URL'), '/')
            . '?' . http_build_query([
                'username' => $user->user_name,
                'password_reset_hash' => $token,
            ]);
        $body = str_replace(
            [ '{USERNAME}', '{RESETLINK}', '{SITENAME}' ],
            [
                $user->user_name,
                $resetLink,
                (string) $this->settings->get('site_name'),
            ],
            $template->body,
        );
        $sent = $this->transport->send(new EmailMessage(
            (string) Config::get('EMAIL_PASSWORD_RESET_SUBJECT'),
            $body,
            [ new EmailRecipient($user->user_email, $user->user_name) ],
        ));
        if (!$sent) {
            $this->error = $this->transport->lastError() ?? 'E-mail verzenden mislukt.';
        }

        return $sent;
    }

    public function verify(string $username, string $token): bool
    {
        $user = $this->users->findByResetToken($username, $token);
        return $user instanceof User
            && $user->user_password_reset_timestamp !== null
            && $user->user_password_reset_timestamp > new DateTimeImmutable('-1 hour');
    }

    public function reset(
        string $username,
        string $token,
        string $password,
        string $confirmation,
    ): bool {
        $this->error = null;
        if ($password !== $confirmation) {
            $this->error = 'De wachtwoorden komen niet overeen.';
            return false;
        }
        if (!Password::isStrongEnough($password)) {
            $this->error = 'Het wachtwoord voldoet niet aan de vereisten.';
            return false;
        }

        $user = $this->users->findByResetToken($username, $token);
        if (
            !$user instanceof User
            || $user->user_password_reset_timestamp === null
            || $user->user_password_reset_timestamp <= new DateTimeImmutable('-1 hour')
        ) {
            $this->error = 'De resetlink is ongeldig of verlopen.';
            return false;
        }

        $user->changePasswordHash(Password::hash($password));
        $user->clearPasswordResetToken();
        $this->users->flush();

        return true;
    }

    public function error(): ?string
    {
        return $this->error;
    }
}
