<?php


declare(strict_types=1);

namespace App\Core\Config;

use PDO;
use App\Core\Database\Database;
use App\Core\Filesystem;
use App\Core\ImageHelper;

class SiteSetting
{
    public static function saveSiteSettings(): bool
    {
        $settings = SiteSettingsFactory::updateRequest();
        foreach ($settings as $setting => $value) {
            SiteSettingsMapper::update($setting, $value);
        }
        return true;
    }

    public static function get(string $setting): ?string
    {
        $stmt = Database::conn()->prepare('SELECT string_value FROM site_settings WHERE setting = ?');
        $stmt->execute([ $setting ]);
        $value = $stmt->fetch(PDO::FETCH_COLUMN);
        if (!empty($value) && $value !== false) {
            return $value;
        }
        return null;
    }

    public function processUploadedLogo($logo): bool
    {
        if (isset($logo) && Filesystem::validateMaxSize($logo, 5000000) && ImageHelper::validateMime($logo, 'image/jpeg')) {
            $resizedImage = ImageHelper::resizeLogo($logo['tmp_name']);
            if ($resizedImage !== null && Filesystem::isWriteableFolder(Config::get('PATH_LOGO'))) {
                ImageHelper::writeJPG($resizedImage, Config::get('PATH_LOGO') . 'logo.jpg');
                $this->writeLogoPathToDatabase(Config::get('URL') . Config::get('PATH_LOGO_PUBLIC') . 'logo.jpg');
                return true;
            }
        }
        return false;
    }

    public function writeLogoPathToDatabase(string $fileName): bool
    {
        if (SiteSettingsMapper::update('site_logo', $fileName)) {
            return true;
        }
        $this->addFlash('danger','Could not write to database');
        return false;
    }
}
