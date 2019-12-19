<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Config;

use PDO;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

/**
 * Class : SiteSettings (SiteSettings.class.php)
 * Details : SiteSettings.
 */
class SiteSetting
{
    public static function saveSiteSettings()
    {
        self::setSiteSetting((string) Request::post('site_name'), 'site_name');
        self::setSiteSetting((string) Request::post('site_description'), 'site_description');
        self::setSiteSetting((string) Request::post('site_description_type'), 'site_description_type');
        self::setSiteSetting((string) Request::post('site_url'), 'site_url');
        self::setSiteSetting((string) Request::post('site_logo'), 'site_logo');
        self::setSiteSetting((string) Request::post('site_theme'), 'site_theme');
        self::setSiteSetting((string) Request::post('site_layout'), 'site_layout');
        self::setSiteSetting((string) Request::post('WidgetComingEvents'), 'WidgetComingEvents');
        self::setSiteSetting((string) Request::post('WidgetDebug'), 'WidgetDebug');
        self::setSiteSetting((string) Request::post('MailServer'), 'MailServer');
        self::setSiteSetting((string) Request::post('MailServerPort'), 'MailServerPort');
        self::setSiteSetting((string) Request::post('MailServerSecure'), 'MailServerSecure');
        self::setSiteSetting((string) Request::post('MailServerAuth'), 'MailServerAuth');
        self::setSiteSetting((string) Request::post('MailServerUsername'), 'MailServerUsername');
        self::setSiteSetting((string) Request::post('MailServerPassword'), 'MailServerPassword');
        self::setSiteSetting((string) Request::post('MailServerDebug'), 'MailServerDebug');
        self::setSiteSetting((string) Request::post('MailFromName'), 'MailFromName');
        self::setSiteSetting((string) Request::post('MailFromEmail'), 'MailFromEmail');
        self::setSiteSetting((string) Request::post('MailIsHTML'), 'MailIsHTML');
        return true;
    }

    public static function setSiteSetting($value, string $setting): bool
    {
        $stmt = DB::conn()->prepare('UPDATE site_settings SET string_value = ? WHERE setting = ?');
        if (!$stmt->execute([$value, $setting])) {
            return false;
        }
        return true;
    }

    public static function getStaticSiteSetting($setting)
    {
        $stmt = DB::conn()->prepare('SELECT string_value FROM site_settings WHERE setting = ?');
        $stmt->execute([$setting]);
        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    public static function uploadLogo(): bool
    {
        if (!self::isLogoFolderWritable()) {
            return false;
        }
        if (!self::validateImageFile()) {
            return false;
        }
        $publicPath = Config::get('URL') . Config::get('PATH_LOGO_PUBLIC') . 'logo';
        $resizedImage = self::resizeLogo($_FILES['logo_file']['tmp_name']);
        if (!empty($resizedImage)) {
            self::writeJPG($resizedImage, Config::get('PATH_LOGO') . 'logo');
            self::writeLogoToDatabase($publicPath . '.jpg');
            return true;
        }
        return false;
    }

    public static function writeJPG($image, $destination)
    {
        $destination .= '.jpg';
        imagejpeg($image, $destination, 100);
        imagedestroy($image);
        if (file_exists($destination)) {
            return true;
        }
        return false;
    }

    public static function isLogoFolderWritable(): bool
    {
        $path_logo = Config::get('PATH_LOGO');
        if (!is_dir(Config::get('PATH_LOGO'))) {
            Session::add('feedback_negative', 'Directory ' . $path_logo . ' doesnt exist');
            return false;
        }
        if (!is_writable(Config::get('PATH_LOGO'))) {
            Session::add('feedback_negative', 'Directory ' . $path_logo . ' is not writeable');
            return false;
        }
        return true;
    }

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

    public static function writeLogoToDatabase($fileName): bool
    {
        $stmt = DB::conn()->prepare("UPDATE site_settings SET string_value = ? WHERE setting = 'site_logo' LIMIT 1");
        if (!$stmt->execute([$fileName])) {
            Session::add('feedback_negative', 'Could not write to database');
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
        [$width, $height] = getimagesize($source);
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
        if ($thumb !== false && !empty($thumb) && $myImage !== false && !empty($myImage)) {
            imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, 150, 150, $smallestSide, $smallestSide);
            return $thumb;
        }
        return null;
    }
}
