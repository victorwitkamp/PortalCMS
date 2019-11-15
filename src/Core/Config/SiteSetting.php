<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Config;

/**
 * Class : SiteSettings (SiteSettings.class.php)
 * Details : SiteSettings.
 */

use PDO;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

class SiteSetting
{
    public static function saveSiteSettings()
    {
        self::setSiteSetting(Request::post('site_name'), 'site_name');
        self::setSiteSetting(Request::post('site_description'), 'site_description');
        self::setSiteSetting(Request::post('site_description_type'), 'site_description_type');
        self::setSiteSetting(Request::post('site_url'), 'site_url');
        self::setSiteSetting(Request::post('site_logo'), 'site_logo');
        self::setSiteSetting(Request::post('site_theme'), 'site_theme');
        self::setSiteSetting(Request::post('site_layout'), 'site_layout');
        self::setSiteSetting(Request::post('WidgetComingEvents'), 'WidgetComingEvents');
        self::setSiteSetting(Request::post('WidgetDebug'), 'WidgetDebug');

        self::setSiteSetting(Request::post('MailServer'), 'MailServer');
        self::setSiteSetting(Request::post('MailServerPort'), 'MailServerPort');
        self::setSiteSetting(Request::post('MailServerSecure'), 'MailServerSecure');
        self::setSiteSetting(Request::post('MailServerAuth'), 'MailServerAuth');
        self::setSiteSetting(Request::post('MailServerUsername'), 'MailServerUsername');
        self::setSiteSetting(Request::post('MailServerPassword'), 'MailServerPassword');
        self::setSiteSetting(Request::post('MailServerDebug'), 'MailServerDebug');
        self::setSiteSetting(Request::post('MailFromName'), 'MailFromName');
        self::setSiteSetting(Request::post('MailFromEmail'), 'MailFromEmail');
        return true;
    }

    public static function setSiteSetting($value, $setting): bool
    {
        $stmt = DB::conn()->prepare('UPDATE site_settings SET string_value = ? WHERE setting = ?');
        if (!$stmt->execute([$value, $setting])) {
            return false;
        }
        return true;
    }

    public static function getStaticSiteSetting($setting)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM site_settings WHERE setting = ?');
        $stmt->execute([$setting]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['string_value'];
    }

    /**
     * Perform the upload of the avatar
     * Authentication::checkAuthentication() makes sure that only logged in users can use this action and see this page
     * POST-request
     */
    public static function uploadLogo(): bool
    {
        Authentication::checkAuthentication();
        if (self::createLogo()) {
            return true;
        }
        return false;
        // Redirect::to('login/editAvatar');
    }

    /**
     * Create an avatar picture (and checks all necessary things too)
     * TODO decouple
     * TODO total rebuild
     */
    public static function createLogo(): bool
    {
        if (!self::isLogoFolderWritable()) {
            return false;
        }
        if (!self::validateImageFile()) {
            return false;
        }
        $target_file_path = Config::get('PATH_LOGO') . 'logo';
        $target_file_path_public = Config::get('URL') . Config::get('PATH_LOGO_PUBLIC') . 'logo';
        self::resizeLogo(
            $_FILES['logo_file']['tmp_name'],
            $target_file_path,
            Config::get('AVATAR_SIZE'),
            Config::get('AVATAR_SIZE'),
            Config::get('AVATAR_JPEG_QUALITY')
        );
        self::writeLogoToDatabase($target_file_path_public . '.jpg');
        return true;
    }

    /**
     * Checks if the avatar folder exists and is writable
     *
     * @return bool success status
     */
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
        //Session::add('feedback_positive', 'Directory '.$path_logo.' exists and is writeable');
        return true;
    }

    /**
     * Validates the image
     * TODO totally decouple
     *
     * @return bool
     */
    public static function validateImageFile(): bool
    {
        if (!isset($_FILES['logo_file'])) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
            return false;
        }
        if ($_FILES['logo_file']['size'] > 5000000) {
            // if input file too big (>5MB)
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG'));
            return false;
        }
        // get the image width, height and mime type
        $image_proportions = getimagesize($_FILES['logo_file']['tmp_name']);
        // if input file too small
        if ($image_proportions[0] < Config::get('AVATAR_SIZE') || $image_proportions[1] < Config::get('AVATAR_SIZE')) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_SMALL'));
            return false;
        }
        if (!($image_proportions['mime'] == 'image/jpeg')) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE'));
            return false;
        }
        return true;
    }

    /**
     * Writes marker to database, saying user has an avatar now
     *
     * @param $fileName
     * @return bool
     * @return bool
     */
    public static function writeLogoToDatabase($fileName): bool
    {
        $stmt = DB::conn()->prepare("UPDATE site_settings SET string_value = ? WHERE setting = 'site_logo' LIMIT 1");
        if (!$stmt->execute([$fileName])) {
            Session::add('feedback_negative', 'DB error');
            return false;
        }
        Session::add('feedback_negative', 'write to db succes');
        return true;
    }

    /**
     * Resize avatar image (while keeping aspect ratio and cropping it off sexy)
     *
     * TROUBLESHOOTING: You don't see the new image ? Press F5 or CTRL-F5 to refresh browser cache.
     *
     * @param string $source The location to the original raw image.
     * @param string $destination  The location to save the new image.
     * @param int    $final_width  The desired width of the new image
     * @param int    $final_height The desired height of the new image.
     * @param int    $quality      The quality of the JPG to produce 1 - 100
     *                             TODO currently we just allow .jpgTODO currently we just allow .jpg
     *
     * @return bool success state
     */
    public static function resizeLogo(string $source, string $destination, $final_width = 150, $final_height = 150): bool
    {
        [$width, $height] = getimagesize($source);
        if (!$width || !$height) {
            return false;
        }
        //saving the image into memory (for manipulation with GD Library)
        $myImage = imagecreatefromjpeg($source);
        // calculating the part of the image to use for thumbnail
        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
        }
        // copying the part into thumbnail, maybe edit this for square avatars
        $thumb = imagecreatetruecolor($final_width, $final_height);
        imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $final_width, $final_height, $smallestSide, $smallestSide);
        // add '.jpg' to file path, save it as a .jpg file with our $destination_filename parameter
        $destination .= '.jpg';
        imagejpeg($thumb, $destination, 100);
        // delete "working copy"
        imagedestroy($thumb);
        if (file_exists($destination)) {
            return true;
        }
        return false;
    }
}
