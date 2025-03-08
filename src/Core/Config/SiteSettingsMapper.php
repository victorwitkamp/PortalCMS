<?php
declare(strict_types=1);


namespace App\Core\Config;

use App\Core\Database\Database;

class SiteSettingsMapper
{
    public static function update(string $setting, string $value): bool
    {
        $stmt = Database::conn()->prepare('UPDATE site_settings SET string_value = ? WHERE setting = ? LIMIT 1');
        if (!$stmt->execute([ $value, $setting ])) {
            return false;
        }
        return true;
    }
}
