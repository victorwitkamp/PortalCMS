<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings\Application;

use GdImage;
use PortalCMS\Core\Config\Config;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class SiteLogo
{
    private const MAX_FILE_SIZE = 5_000_000;
    private const MAX_PIXELS = 25_000_000;
    private const MIME_TYPES = [
        'image/gif',
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public function replace(?UploadedFile $file): string
    {
        $directory = (string) Config::get('PATH_LOGO');
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new RuntimeException('De logomap bestaat niet of is niet schrijfbaar.');
        }
        if (
            !$file instanceof UploadedFile
            || !$file->isValid()
            || $file->getSize() > self::MAX_FILE_SIZE
            || !in_array($file->getMimeType(), self::MIME_TYPES, true)
        ) {
            throw new RuntimeException(
                'Selecteer een geldige JPEG-, PNG-, GIF- of WebP-afbeelding van maximaal 5 MB.',
            );
        }

        $resizedImage = $this->resize($file->getPathname());
        $destination = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . 'logo.jpg';
        $written = imagejpeg($resizedImage, $destination, 100);
        imagedestroy($resizedImage);
        if (!$written || !is_file($destination)) {
            throw new RuntimeException('De afbeelding kon niet worden opgeslagen.');
        }

        return rtrim((string) Config::get('URL'), '/')
            . '/'
            . trim((string) Config::get('PATH_LOGO_PUBLIC'), '/')
            . '/logo.jpg';
    }

    private function resize(string $source): GdImage
    {
        $size = getimagesize($source);
        if (
            $size === false
            || !isset($size['mime'])
            || !in_array($size['mime'], self::MIME_TYPES, true)
        ) {
            throw new RuntimeException('De afbeelding kon niet worden gelezen.');
        }

        [ $width, $height ] = $size;
        if (
            $width <= 0
            || $height <= 0
            || $width > intdiv(self::MAX_PIXELS, $height)
        ) {
            throw new RuntimeException('De afbeelding heeft te grote afmetingen.');
        }

        $contents = file_get_contents($source);
        $image = $contents !== false ? imagecreatefromstring($contents) : false;
        if ($image === false) {
            throw new RuntimeException('De afbeelding kon niet worden gelezen.');
        }

        $smallestSide = min($width, $height);
        $thumbnail = imagecreatetruecolor(150, 150);
        if ($thumbnail === false) {
            imagedestroy($image);
            throw new RuntimeException('De afbeelding kon niet worden verkleind.');
        }
        imagealphablending($thumbnail, true);
        imagefill($thumbnail, 0, 0, imagecolorallocate($thumbnail, 255, 255, 255));

        imagecopyresampled(
            $thumbnail,
            $image,
            0,
            0,
            (int) (($width - $smallestSide) / 2),
            (int) (($height - $smallestSide) / 2),
            150,
            150,
            $smallestSide,
            $smallestSide,
        );
        imagedestroy($image);

        return $thumbnail;
    }
}
