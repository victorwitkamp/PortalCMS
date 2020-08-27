<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\HTTP;

use function is_string;

/**
 * Request Class
 * Abstracts the access to $_GET, $_POST and $_COOKIE, preventing direct access to these super-globals.
 */
class Request
{
    public function __construct()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);
        preg_match_all('/_[a-z]/', $result, $matches);
        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }
        return $result;
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    public function getBody()
    {
        $data = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $data;
    }

    /**
     * When using just Request::post('x') it will return the raw and untouched $_POST['x'], when using it like
     * Request::post('x', true) will return a trimmed and stripped $_POST['x'] !
     * @return mixed|string|null
     */
    public static function post($key, bool $clean = false)
    {
        if (isset($_POST[$key]) && !empty($_POST[$key])) {
            if ($clean && is_string($key)) {
                $return = trim(strip_tags($_POST[$key]));
            } else {
                $return = $_POST[$key];
            }
            if (!empty($return)) {
                return $return;
            }
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public static function get($key)
    {
        return $_GET[$key] ?? null;
    }

    /**
     * @return mixed|null
     */
    public static function cookie($key)
    {
        return $_COOKIE[$key] ?? null;
    }
}
