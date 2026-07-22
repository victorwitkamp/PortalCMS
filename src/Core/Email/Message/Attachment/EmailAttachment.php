<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Message\Attachment;

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EmailAttachment
{
    private const MAX_FILE_SIZE = 5000000; // 5MB

    public $path;
    public $name;
    public $extension;
    public $encoding = 'base64';
    public $type = 'application/octet-stream';

    public function __construct(?UploadedFile $file)
    {
        $this->path = Config::get('PATH_ATTACHMENTS');
        $this->processUpload($file);
    }

    public function processUpload(?UploadedFile $file): bool
    {
        if ($file !== null && $file->isValid()) {
            if ($this->validateFileSize($file) && $this->isFolderWritable($this->path)) {
                $filename = $file->getClientOriginalName();
                try {
                    $movedFile = $file->move(DIR_ROOT . $this->path, $filename);
                    $this->name = pathinfo($filename, PATHINFO_FILENAME);
                    $this->extension = pathinfo($filename, PATHINFO_EXTENSION);
                    $this->type = $movedFile->getMimeType() ?? $this->type;
                    return true;
                } catch (\Throwable $e) {
                    Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
                }
            }
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
        }
        return false;
    }

    /**
     * Validates that the file size of the attachment is within range.
     */
    public function validateFileSize(UploadedFile $file): bool
    {
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG'));
            return false;
        }
        return true;
    }

    /**
     * Checks if the upload folder exists and if it is writable
     * @return bool success status
     * @var string $path Path of the target upload folder
     */
    public function isFolderWritable(string $path): bool
    {
        if (is_dir(DIR_ROOT . $path)) {
            if (is_writable(DIR_ROOT . $path)) {
                return true;
            }
            Session::add('feedback_negative', 'Directory ' . $path . ' is not writeable');
        } else {
            Session::add('feedback_negative', 'Directory ' . $path . ' doesnt exist');
        }
        return false;
    }

    /**
     * Delete attachment(s)
     * @param array|null $attachmentIds
     * @return bool
     */
    public static function deleteById(array $attachmentIds = null): bool
    {
        $deleted = 0;
        $error = 0;
        if (empty($attachmentIds)) {
            Session::add('feedback_negative', 'Verwijderen mislukt. Ongeldig verzoek');
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

    /**
     * Handle feedback for the deleteById method
     * @param int $deleted
     * @param int $error
     * @return bool
     */
    public static function deleteFeedbackHandler(int $deleted, int $error): bool
    {
        if ($deleted > 0) {
            if ($error === 0) {
                Session::add('feedback_positive', 'Aantal bijlagen verwijderd: ' . $deleted);
            }
            if ($error > 0) {
                Session::add('feedback_warning', 'Aantal bijlagen verwijderd: ' . $deleted . '. Aantal bijlagen met problemen: ' . $error);
            }
            return true;
        }
        Session::add('feedback_negative', 'Verwijderen mislukt. Aantal bijlagen met problemen: ' . $error);
        return false;
    }

    public function store(int $mailId = null, int $templateId = null): bool
    {
        if (!$this->validate()) {
            Session::add('feedback_negative', Text::get('FEEDBACK_MAIL_ATTACHMENT_UPLOAD_FAILED'));
        } elseif (!empty($mailId) && empty($templateId)) {
            // No implementation yet
            Session::add('feedback_negative', Text::get('FEEDBACK_MAIL_ATTACHMENT_UPLOAD_FAILED'));
        } elseif (empty($mailId) && !empty($templateId) && EmailAttachmentMapper::createForTemplate($templateId, $this)) {
            Session::add('feedback_positive', Text::get('FEEDBACK_MAIL_ATTACHMENT_UPLOAD_SUCCESSFUL'));
            return true;
        }
        return false;
    }

    public function validate(): bool
    {
        return !(empty($this->path) || empty($this->name) || empty($this->extension) || empty($this->encoding) || empty($this->type));
    }
}
