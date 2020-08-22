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

    public static function get($key, $data = null) : ?string
    {
        if ($data !== null) {
            foreach ($data as $var => $value) {
                ${$var} = $value;
            }
        }
        if (!self::$texts) {
            self::$texts = include DIR_CONFIG . 'texts.php';
        }
        return (!array_key_exists($key, self::$texts)) ? 'LABEL_NOT_FOUND' : strtoupper(self::$texts[$key]);
    }
}
