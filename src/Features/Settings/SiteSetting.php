<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings;

use GdImage;
use PortalCMS\Core\Config\Config;
use PortalCMS\Core\View\Text;
use PortalCMS\Features\Settings\Repository\SiteSettingRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class SiteSetting
{
    private ?string $error = null;

    public const EDITABLE_SETTINGS = [
        'site_name',
        'site_description',
        'site_description_type',
        'site_url',
        'site_logo',
        'site_theme',
        'site_layout',
        'WidgetComingEvents',
        'WidgetDebug',
        'MailServer',
        'MailServerPort',
        'MailServerSecure',
        'MailServerAuth',
        'MailServerUsername',
        'MailServerPassword',
        'MailServerDebug',
        'MailFromName',
        'MailFromEmail',
        'MailIsHTML',
    ];

    public function __construct(private readonly SiteSettingRepository $repository)
    {
    }

    /**
     * @param array<string, string|null> $settings
     */
    public function save(array $settings): bool
    {
        $success = true;
        foreach ($settings as $setting => $value) {
            $entity = $this->repository->findSetting($setting);
            if ($entity === null) {
                $success = false;
                continue;
            }
            $entity->changeValue($value);
        }
        $this->repository->flush();

        return $success;
    }

    public function get(string $setting): ?string
    {
        return $this->repository->findValue($setting);
    }

    /**
     * @param string[] $names
     * @return array<string, string|null>
     */
    public function values(array $names = self::EDITABLE_SETTINGS): array
    {
        $values = [];
        foreach ($names as $name) {
            $values[$name] = $this->get($name);
        }

        return $values;
    }

    public function uploadLogo(?UploadedFile $file): bool
    {
        $this->error = null;
        if (!$this->isLogoFolderWritable() || !$this->validateImageFile($file)) {
            return false;
        }

        $resizedImage = $this->resizeLogo($file->getPathname());
        if ($resizedImage === null) {
            $this->error = 'The image could not be resized.';
            return false;
        }

        $destination = Config::get('PATH_LOGO') . 'logo.jpg';
        if (!$this->writeJpeg($resizedImage, $destination)) {
            $this->error = 'The image could not be written.';
            return false;
        }

        $publicPath = Config::get('URL') . Config::get('PATH_LOGO_PUBLIC') . 'logo.jpg';
        $setting = $this->repository->findSetting('site_logo');
        if ($setting === null) {
            $this->error = 'Could not write to database.';
            return false;
        }
        $setting->changeValue($publicPath);
        $this->repository->flush();

        return true;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    private function isLogoFolderWritable(): bool
    {
        $path = Config::get('PATH_LOGO');
        if (!is_dir($path)) {
            $this->error = 'Directory ' . $path . ' does not exist.';
            return false;
        }
        if (!is_writable($path)) {
            $this->error = 'Directory ' . $path . ' is not writable.';
            return false;
        }

        return true;
    }

    private function validateImageFile(?UploadedFile $file): bool
    {
        if ($file === null || !$file->isValid()) {
            $this->error = (string) Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED');
            return false;
        }
        if ($file->getSize() > 5_000_000) {
            $this->error = (string) Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG');
            return false;
        }
        if ($file->getMimeType() !== 'image/jpeg') {
            $this->error = (string) Text::get('FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE');
            return false;
        }

        return true;
    }

    private function resizeLogo(string $source): ?GdImage
    {
        $size = getimagesize($source);
        if ($size === false) {
            return null;
        }
        [ $width, $height ] = $size;
        $image = imagecreatefromjpeg($source);
        if ($image === false) {
            return null;
        }

        $smallestSide = min($width, $height);
        $x = ($width - $smallestSide) / 2;
        $y = ($height - $smallestSide) / 2;
        $thumbnail = imagecreatetruecolor(150, 150);
        if ($thumbnail === false) {
            imagedestroy($image);
            return null;
        }

        imagecopyresampled(
            $thumbnail,
            $image,
            0,
            0,
            (int) $x,
            (int) $y,
            150,
            150,
            $smallestSide,
            $smallestSide,
        );
        imagedestroy($image);

        return $thumbnail;
    }

    private function writeJpeg(GdImage $image, string $destination): bool
    {
        $written = imagejpeg($image, $destination, 100);
        imagedestroy($image);

        return $written && file_exists($destination);
    }
}
