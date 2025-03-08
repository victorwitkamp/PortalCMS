<?php


declare(strict_types=1);

namespace App\Core\Session;

use App\Core\Security\Filter;

class Session
{
    public static function init(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function isActive(): bool
    {
        return (session_status() === PHP_SESSION_ACTIVE);
    }

    public static function set(string $key, $value): bool
    {
        $_SESSION[$key] = $value;
        return true;
    }

    public static function get(string $key, bool $filter = true)
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            if ($filter) {
                return Filter::XSSFilter($value);
            }
            return $value;
        }
        return null;
    }

    public static function destroy(): bool
    {
        if (!session_destroy()) {
            self::add('feedback_warning', 'Session could not be destroyed.');
            return false;
        }
        self::add('feedback_warning', 'Session destroyed.');
        return true;
    }

    public static function add(string $key, $value): void
    {
        $_SESSION[$key][] = $value;
        // session_write_close();
    }
}
