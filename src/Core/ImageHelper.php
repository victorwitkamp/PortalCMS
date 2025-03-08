<?php
declare(strict_types=1);

namespace App\Core;

use GdImage;
use App\Core\View\Text;

class ImageHelper
{
    public static function validateMime(array $file = null, string $mimetype = null): bool
    {
        $fileinfo = getimagesize($file['tmp_name']);
        if ($fileinfo['mime'] !== $mimetype) {
            $this->addFlash('danger',Text::get('FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE'));
            return false;
        }
        return true;
    }

    public static function resizeLogo(string $filename): ?GdImage
    {
        [ $width, $height ] = getimagesize($filename);
        if (!empty($width) && !empty($height)) {
            $myImage = imagecreatefromjpeg($filename);

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
        }
        return null;
    }

    public static function writeJPG(GdImage $image = null, string $destination = null): bool
    {
        imagejpeg($image, $destination, 100);
        imagedestroy($image);
        return file_exists($destination);
    }
}
