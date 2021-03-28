<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security;

use function is_array;
use function is_object;
use function is_string;

class Filter
{
    public static function XSSFilter(&$value): mixed
    {
        if (is_string($value)) {
            $value = htmlspecialchars($value, ENT_QUOTES);
        } elseif (is_array($value) || is_object($value)) {
            foreach ($value as &$valueInValue) {
                self::XSSFilter($valueInValue);
            }
        }
        return $value;
    }
}
