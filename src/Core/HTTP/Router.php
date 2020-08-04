<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Core\HTTP;

use function call_user_func;

class Router
{
    public static function processRequests(array $requests, $class): void
    {
        foreach ($requests as $key => $value) {
            if (($value === 'POST') && isset($_POST[$key])) {
                call_user_func([ $class, $key ]);
            }
        }
    }
}
