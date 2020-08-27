<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\HTTP;

use PortalCMS\Core\Security\Filter;

/**
 * Class Session
 * @package PortalCMS\Core\HTTP
 */
class Session
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function init(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     */
    public static function set($key, $value): bool
    {
        $_SESSION[$key] = $value;
        return true;
    }

    /**
     * @return mixed|null
     */
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

    /**
     */
    public static function destroy(): bool
    {
        if (!session_destroy()) {
            self::add('feedback_warning', 'Session could not be destroyed.');
            return false;
        }
        self::add('feedback_warning', 'Session destroyed.');
        return true;
    }

    /**
     */
    public static function add($key, $value): void
    {
        $_SESSION[$key][] = $value;
        // session_write_close();
    }
}
