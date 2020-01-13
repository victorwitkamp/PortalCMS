<?php

/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Activity;

use PortalCMS\Core\Database\DB;

class ActivityMapper
{
    public static function add(string $activity, int $user_id = null, $user_name = null, $ip = null, $details = null): bool
    {
        $sql = 'INSERT INTO activity (id, user_id, user_name, ip_address, activity, details) VALUES (NULL, ?, ?, ?, ?, ?)';
        $stmt = DB::conn()->prepare($sql);
        if ($stmt->execute([$user_id, $user_name, $ip, $activity, $details])) {
            return true;
        }
        return false;
    }

    public static function load(): array
    {
        return DB::conn()->query('SELECT * FROM activity ORDER BY id desc LIMIT 50')->fetchAll();
    }
}
