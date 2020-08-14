<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Message\Attachment;

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

/**
 * Class EmailAttachment
 * @package PortalCMS\Core\Email\Message\Attachment
 */
class EmailAttachment
{
    public $path;
    public $name;
    public $extension;
    public $encoding = 'base64';
    public $type = 'application/octet-stream';

    /**
     * EmailAttachment constructor.
     * @param array $file
     */
    public function __construct(array $file)
    {
        $this->path = Config::get('PATH_ATTACHMENTS');
        $this->processUpload($file);
    }

    /**
     * @param array $file
     * @return bool
     */
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
                Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
            }
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
        }
        return false;
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
     * @param string|null $filename
     */
    public function getMIMEType(string $filename = null): string
    {
        $realpath = realpath($filename);
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $realpath);
    }

    /**
     * Delete attachment(s) by providing an array of attachmentIds
     * @param array|null $attachmentIds
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
     */
    public static function deleteFeedbackHandler(int $deleted = 0, int $error = 0): bool
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

    //    /**
    ////     * Validates is the file size of the attachment is within range.
    ////     * @param $attachmentFile
    ////     * @return bool
    ////     */
    //    public function validateFileSize($attachmentFile) : bool
    //    {
    //        if ($attachmentFile['size'] > 5000000) {
    //            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG'));
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
    //    //        Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE'));
    //    //        return false;
    //    //    }

    /**
     * @param int|null $mailId
     * @param int|null $templateId
     */
    public function store(int $mailId = null, int $templateId = null): bool
    {
        if (!$this->validate()) {
            Session::add('feedback_negative', Text::get('FEEDBACK_MAIL_ATTACHMENT_UPLOAD_FAILED'));
        } elseif ($mailId !== null && $templateId === null) {
            // No implementation yet
            Session::add('feedback_negative', Text::get('FEEDBACK_MAIL_ATTACHMENT_UPLOAD_FAILED'));
        } elseif ($mailId === null && $templateId !== null && EmailAttachmentMapper::createForTemplate($templateId, $this)) {
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
