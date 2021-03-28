<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Session;

use PortalCMS\Core\Security\Filter;

class Session
{
    public static function isActive(): bool
    {
        return session_status() === 2;
    }
    public static function init(): void
    {
        if (!self::isActive()) {
            session_start();
        }
    }
    public static function set($key, $value): bool
    {
        $_SESSION[$key] = $value;
        return true;
    }
    public static function get($key, bool $filter = true)
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

    public static function add($key, $value): void
    {
        $_SESSION[$key][] = $value;
        // session_write_close();
    }
}
