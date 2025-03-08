<?php


declare(strict_types=1);

namespace App\Core\Email\Message\Attachment;

use App\Core\Config\Config;
use App\Core\View\Text;

class EmailAttachment
{
    public string $path;
    public string $name;
    public string $extension;
    public string $encoding = 'base64';
    public string $type = 'application/octet-stream';

    public function __construct(array $file)
    {
        $this->path = Config::get('PATH_ATTACHMENTS');
        $this->processUpload($file);
    }

    public function processUpload(array $file): bool
    {
        if (!empty($file)) {
            if ($this->isFolderWritable($this->path)) {
                // if (!$this->validateType($file)) {
                //     return false;
                // }
                // if (!$this->validateFileSize($file)) {
                //     return false;
                // }
                if (move_uploaded_file($file['tmp_name'], DIR_ROOT . $this->path . $file['name'])) {
                    $this->name = pathinfo($this->path . $file['name'], PATHINFO_FILENAME);
                    $this->extension = pathinfo($this->path . $file['name'], PATHINFO_EXTENSION);
                    $this->type = $this->getMIMEType(DIR_ROOT . $this->path . $file['name']);
                    return true;
                }
                $this->addFlash('danger',Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
            }
        } else {
            $this->addFlash('danger',Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
        }
        return false;
    }

    public function isFolderWritable(string $path): bool
    {
        if (is_dir(DIR_ROOT . $path)) {
            if (is_writable(DIR_ROOT . $path)) {
                return true;
            }
            $this->addFlash('danger','Directory ' . $path . ' is not writeable');
        } else {
            $this->addFlash('danger','Directory ' . $path . ' doesnt exist');
        }
        return false;
    }

    public function getMIMEType(string $filename): string
    {
        $realpath = realpath($filename);
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $realpath);
    }

    public function deleteById(array $attachmentIds = null): bool
    {
        $deleted = 0;
        $error = 0;
        if (empty($attachmentIds)) {
            $this->addFlash('danger','Verwijderen mislukt. Ongeldig verzoek');
            return false;
        }
        foreach ($attachmentIds as $attachmentId) {
            if (EmailAttachmentMapper::deleteById((int)$attachmentId)) {
                ++$deleted;
            } else {
                ++$error;
            }
        }
        return self::deleteFeedbackHandler($deleted, $error);
    }

    public function deleteFeedbackHandler(int $deleted, int $error): bool
    {
        if ($deleted > 0) {
            if ($error === 0) {
                $this->addFlash('success','Aantal bijlagen verwijderd: ' . $deleted);
            }
            if ($error > 0) {
                $this->addFlash('warning', 'Aantal bijlagen verwijderd: ' . $deleted . '. Aantal bijlagen met problemen: ' . $error);
            }
            return true;
        }
        $this->addFlash('danger','Verwijderen mislukt. Aantal bijlagen met problemen: ' . $error);
        return false;
    }

//    /**
    ////     * Validates is the file size of the attachment is within range.
    ////     * @param $attachmentFile
    ////     * @return bool
    ////     */
    //    public function validateFileSize($attachmentFile) : bool
    //    {
    //        if ($attachmentFile['size'] > 5000000) {
    //            $this->addFlash('danger',Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG'));
    //            return false;
    //        }
    //        return true;
    //    }

    //    public function validateImageDimentions($attachmentFile) : bool
    //    {
    //         $image_proportions = getimagesize($attachmentFile['tmp_name']);
    //         if ($image_proportions[0] < Config::get('AVATAR_SIZE') or $image_proportions[1] < Config::get('AVATAR_SIZE')) {
    //             return false;
    //         }
    //         return true
    //    }

    //    public function validateType($attachmentFile) : bool
    //    //    {
    //    //        if ($attachmentFile['type'] === 'image/jpeg') {
    //    //            return true;
    //    //        }
    //    //        $this->addFlash('danger',Text::get('FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE'));
    //    //        return false;
    //    //    }

    public function store(int $mailId = null, int $templateId = null): bool
    {
        if (!$this->validate()) {
            $this->addFlash('danger',Text::get('FEEDBACK_MAIL_ATTACHMENT_UPLOAD_FAILED'));
        } elseif ($mailId !== null && $templateId === null) {
            // No implementation yet
            $this->addFlash('danger',Text::get('FEEDBACK_MAIL_ATTACHMENT_UPLOAD_FAILED'));
        } elseif ($mailId === null && $templateId !== null && EmailAttachmentMapper::createForTemplate($templateId, $this)) {
            $this->addFlash('success',Text::get('FEEDBACK_MAIL_ATTACHMENT_UPLOAD_SUCCESSFUL'));
            return true;
        }
        return false;
    }

    public function validate(): bool
    {
        return !(empty($this->path) || empty($this->name) || empty($this->extension) || empty($this->encoding) || empty($this->type));
    }
}
