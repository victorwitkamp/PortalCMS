<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\View;

use function array_key_exists;

/**
 * Class Text
 * @package PortalCMS\Core\View
 */
class Text
{
    private static $texts;

    /**
     * @param      $key
     * @param null $data
     * @return string|null
     */
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
            self::$texts = include DIR_CONFIG . 'texts.php';
        }

        if (!array_key_exists($key, self::$texts)) {
            return 'LABEL_NOT_FOUND';
        }

        return strtoupper(self::$texts[$key]);
    }
}
