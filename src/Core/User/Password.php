<?php


declare(strict_types=1);

namespace App\Core\User;

class Password
{
    public static function verifyPassword($user, string $user_password): bool
    {
        return password_verify(base64_encode($user_password), $user->user_password_hash);
    }
}
