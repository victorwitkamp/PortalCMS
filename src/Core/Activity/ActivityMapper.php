<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Activity;

use PortalCMS\Core\Database\Database;

class ActivityMapper
{
    public static function add(string $activity, int $user_id = null, string $user_name = null, string $ip = null, string $details = null) : bool
    {
        $sql = 'INSERT INTO activity (id, user_id, user_name, ip_address, activity, details) VALUES (NULL, ?, ?, ?, ?, ?)';
        $stmt = Database::conn()->prepare($sql);
        if ($stmt->execute([$user_id, $user_name, $ip, $activity, $details])) {
            return true;
        }
        return false;
    }

    public static function load(): array
    {
        return Database::conn()->query('SELECT * FROM activity ORDER BY id desc LIMIT 50')->fetchAll();
    }
}
