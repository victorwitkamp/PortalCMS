<?php
/**
 * Copyright Victor Witkamp (c) 2019.
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

    public static function add(string $activity, int $user_id = null, $details = null, $user_name = null)
    {
        if (!empty($activity)) {
            $remoteAddress = new RemoteAddress();
            $clientIp = $remoteAddress->getIpAddress();
            ActivityMapper::add($activity, $user_id, $user_name, $clientIp, $details);
        }
    }
}
