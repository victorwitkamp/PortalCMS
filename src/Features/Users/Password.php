<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users;

use PortalCMS\Core\View\Text;
use PortalCMS\Features\Users\Entity\User;
use PortalCMS\Features\Users\Repository\UserRepository;

final class Password
{
    public function __construct(private readonly UserRepository $users)
    {
    }

    /**
     * Returns null on success or the user-facing validation error on failure.
     */
    public function change(
        User $user,
        string $currentPassword,
        string $newPassword,
        string $confirmation,
    ): ?string {
        if ($currentPassword === '' || $newPassword === '' || $confirmation === '') {
            return (string) Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY');
        }
        if ($newPassword !== $confirmation) {
            return (string) Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG');
        }
        if (!self::verify($user, $currentPassword)) {
            return (string) Text::get('FEEDBACK_PASSWORD_CURRENT_INCORRECT');
        }
        if ($currentPassword === $newPassword) {
            return (string) Text::get('FEEDBACK_PASSWORD_NEW_SAME_AS_CURRENT');
        }
        if (!self::isStrongEnough($newPassword)) {
            return (string) Text::get('FEEDBACK_PASSWORD_TOO_SHORT');
        }

        $user->changePasswordHash(self::hash($newPassword));
        $this->users->flush();

        return null;
    }

    public static function verify(User $user, string $password): bool
    {
        return $user->user_password_hash !== null
            && password_verify(base64_encode($password), $user->user_password_hash);
    }

    public static function hash(string $password): string
    {
        return password_hash(base64_encode($password), PASSWORD_DEFAULT);
    }

    public static function isStrongEnough(string $password): bool
    {
        return strlen($password) >= 8
            && preg_match('/[a-z]/', $password) === 1
            && preg_match('/[A-Z]/', $password) === 1
            && preg_match('/\d/', $password) === 1
            && preg_match('/\s/', $password) !== 1;
    }
}
