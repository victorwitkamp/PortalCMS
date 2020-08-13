<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Config;

use PDO;
use PortalCMS\Core\Database\Database;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

/**
 * Class : SiteSettings (SiteSettings.class.php)
 * Details : SiteSettings.
 */
class SiteSetting
{
    /**
     * @return bool
     */
    public static function saveSiteSettings(): bool
    {
        $settings = SiteSettingsFactory::updateRequest();
        foreach ($settings as $setting => $value) {
            SiteSettingsMapper::update($setting, $value);
        }
        return true;
    }

    /**
     * @param string $setting
     * @return string|null
     */
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

    /**
     * @return bool
     */
    public static function uploadLogo(): bool
    {
        if (self::isLogoFolderWritable() && self::validateImageFile()) {
            $publicPath = Config::get('URL') . Config::get('PATH_LOGO_PUBLIC') . 'logo';
            $resizedImage = self::resizeLogo($_FILES['logo_file']['tmp_name']);
            if (!empty($resizedImage)) {
                self::writeJPG($resizedImage, Config::get('PATH_LOGO') . 'logo');
                self::writeLogoToDatabase($publicPath . '.jpg');
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function isLogoFolderWritable(): bool
    {
        if (!is_dir(Config::get('PATH_LOGO'))) {
            Session::add('feedback_negative', 'Directory ' . Config::get('PATH_LOGO') . ' doesnt exist');
        } elseif (!is_writable(Config::get('PATH_LOGO'))) {
            Session::add('feedback_negative', 'Directory ' . Config::get('PATH_LOGO') . ' is not writeable');
        } else {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function validateImageFile(): bool
    {
        if (!isset($_FILES['logo_file'])) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
            return false;
        }
        if ($_FILES['logo_file']['size'] > 5000000) { // >5MB
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG'));
            return false;
        }
        $image_proportions = getimagesize($_FILES['logo_file']['tmp_name']);
        if (!($image_proportions['mime'] === 'image/jpeg')) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE'));
            return false;
        }
        return true;
    }

    /**
     * Resize logo
     * @param string $source The location to the original raw image.
     * @return resource success state
     */
    public static function resizeLogo(string $source)
    {
        [ $width, $height ] = getimagesize($source);
        if (!$width || !$height) {
            return null;
        }

        $myImage = imagecreatefromjpeg($source);

        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
        }

        $thumb = imagecreatetruecolor(150, 150);
        if ($thumb !== false && $myImage !== false) {
            imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, 150, 150, $smallestSide, $smallestSide);
            return $thumb;
        }
        return null;
    }

    /**
     * @param        $image
     * @param string $destination
     * @return bool
     */
    /**
     * @param        $image
     * @param string $destination
     * @return bool
     */
    /**
     * @param        $image
     * @param string $destination
     * @return bool
     */
    public static function writeJPG($image, string $destination): bool
    {
        $destination .= '.jpg';
        imagejpeg($image, $destination, 100);
        imagedestroy($image);
        if (file_exists($destination)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $fileName
     * @return bool
     */
    /**
     * @param string $fileName
     * @return bool
     */
    /**
     * @param string $fileName
     * @return bool
     */
    public static function writeLogoToDatabase(string $fileName): bool
    {
        if (SiteSettingsMapper::update('site_logo', $fileName)) {
            return true;
        }
        Session::add('feedback_negative', 'Could not write to database');
        return false;
    }
}
