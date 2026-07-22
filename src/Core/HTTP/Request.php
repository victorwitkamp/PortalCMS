<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\HTTP;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Request Class
 * Abstracts access to the current HTTP request, backed by Symfony's
 * HttpFoundation Request instead of touching $_GET/$_POST/$_COOKIE/$_FILES
 * directly.
 */
class Request
{
    private static ?SymfonyRequest $instance = null;

    public static function instance(): SymfonyRequest
    {
        if (self::$instance === null) {
            self::$instance = SymfonyRequest::createFromGlobals();
        }
        return self::$instance;
    }

    /**
     * When using just Request::post('x') it will return the raw and untouched POST value, when using it like
     * Request::post('x', true) it will return a trimmed and stripped value!
     */
    public static function post(string $key, bool $clean = false)
    {
        $value = self::instance()->request->get($key);
        if (empty($value)) {
            return null;
        }
        if ($clean) {
            $value = trim(strip_tags((string)$value));
        }
        return !empty($value) ? $value : null;
    }

    public static function get(string $key)
    {
        return self::instance()->query->get($key);
    }

    public static function cookie(string $key)
    {
        return self::instance()->cookies->get($key);
    }

    public static function file(string $key): ?UploadedFile
    {
        $file = self::instance()->files->get($key);
        return $file instanceof UploadedFile ? $file : null;
    }
}
