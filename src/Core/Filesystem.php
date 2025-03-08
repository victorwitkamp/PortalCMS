<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\View\Text;

class Filesystem
{
    public function __construct()
    {
    }

    public static function isWriteableFolder(string $path = null): bool
    {
        if (!is_dir($path)) {
            $this->addFlash('danger','Directory ' . $path . ' doesnt exist');
        } elseif (!is_writable($path)) {
            $this->addFlash('danger','Directory ' . $path . ' is not writeable');
        } else {
            return true;
        }
        return false;
    }

    public static function validateMaxSize(array $file = null, int $size = null): bool
    {
        if ($file['size'] > $size) {
            $this->addFlash('danger',Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG'));
            return false;
        }
        return true;
    }
}
