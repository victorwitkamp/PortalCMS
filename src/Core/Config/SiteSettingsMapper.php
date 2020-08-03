<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Core\Config;

use PortalCMS\Core\Database\Database;

class SiteSettingsMapper
{
    public static function update(string $setting, string $value): bool
    {
        $stmt = Database::conn()->prepare('UPDATE site_settings SET string_value = ? WHERE setting = ? LIMIT 1');
        if (!$stmt->execute([$value, $setting])) {
            return false;
        }
        return true;
    }
}
