<?php
declare(strict_types=1);

namespace PortalCMS\Core\View;

use function array_key_exists;

class Text
{
    private static $texts;

    public static function get($key, $data = null)
    {
        if (!$key) {
            return null;
        }

        if ($data) {
            foreach ($data as $var => $value) {
                ${$var} = $value;
            }
        }

        if (!self::$texts) {
            self::$texts = include DIR_ROOT . 'config/texts.php';
        }

        if (!array_key_exists($key, self::$texts)) {
            return '!! LABEL NOT FOUND !!';
        }

        return self::$texts[$key];
    }
}
