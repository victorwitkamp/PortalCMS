<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Activity;

use PortalCMS\Core\HTTP\RemoteAddress;

/**
 * Class : Activity (Activity.php)
 * Details :
 */
class Activity
{
    public static function load(): array
    {
        return ActivityMapper::load();
    }

    public static function add(string $activity, int $user_id = null, string $details = null, string $user_name = null) : bool
    {
        if (!empty($activity)) {
            $remoteAdd = new RemoteAddress();
            if (ActivityMapper::add($activity, $user_id, $user_name, $remoteAdd->getIpAddress(), $details)) {
                return true;
            }
        }
        return false;
    }
}
