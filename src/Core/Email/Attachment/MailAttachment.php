<?php

namespace PortalCMS\Core\Email\Attachment;

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

class MailAttachment
{
    public static function uploadAttachment() : bool
    {
        if (!isset($_FILES['attachment_file'])) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
            return false;
        }
        if (!self::validateType()) {
            return false;
        }
        if (!self::validateFileSize()) {
            return false;
        }
        if (!self::isFolderWritable()) {
            return false;
        }
        $targetPath = Config::get('PATH_ATTACHMENTS') . $_FILES['attachment_file']['name'];
        if (!move_uploaded_file($_FILES['attachment_file']['tmp_name'], $targetPath)) {
            return false;
        }
        $mime = self::getMIMEType($targetPath);
        MailAttachmentMapper::createForTemplate(
            Request::get('id'),
            Config::get('PATH_ATTACHMENTS_PUBLIC'),
            pathinfo($targetPath, PATHINFO_FILENAME),
            '.' . pathinfo($targetPath, PATHINFO_EXTENSION),
            'base64',
            $mime
        );
        Session::add('feedback_positive', Text::get('FEEDBACK_MAIL_ATTACHMENT_UPLOAD_SUCCESSFUL'));
        return true;
    }

    public static function getMIMEType($filename)
    {
        $realpath = realpath($filename);
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $realpath);
    }

    /**
     * Checks if the avatar folder exists and is writable
     *
     * @return bool success status
     */
    public static function isFolderWritable() : bool
    {
        $path_attachment = Config::get('PATH_ATTACHMENTS');
        if (!is_dir(Config::get('PATH_ATTACHMENTS'))) {
            Session::add('feedback_negative', 'Directory ' . $path_attachment . ' doesnt exist');
            return false;
        }
        if (!is_writable(Config::get('PATH_ATTACHMENTS'))) {
            Session::add('feedback_negative', 'Directory ' . $path_attachment . ' is not writeable');
            return false;
        }
        return true;
    }

    /**
     * Validates is the file size of the attachment is within range.
     *
     * @return bool
     */
    public static function validateFileSize() : bool
    {
        if ($_FILES['attachment_file']['size'] > 5000000) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG'));
            return false;
        }
        // $image_proportions = getimagesize($_FILES['attachment_file']['tmp_name']);
        // if ($image_proportions[0] < Config::get('AVATAR_SIZE') or $image_proportions[1] < Config::get('AVATAR_SIZE')) {
        //     return false;
        // }
        return true;
    }

    public static function validateType() : bool
    {
        if ($_FILES['attachment_file']['type'] === 'image/jpeg') {
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE'));
        return false;
    }

    /**
     * Delete attachment(s)
     *
     * @param array|int $attachmentIds
     *
     * @return bool
     */
    public static function deleteById($attachmentIds) : bool
    {
        $deleted = 0;
        $error = 0;
        if (empty($attachmentIds)) {
            Session::add('feedback_negative', 'Verwijderen mislukt. Ongeldig verzoek');
            return false;
        }
        foreach ($attachmentIds as $attachmentId) {
            if (!MailAttachmentMapper::deleteById($attachmentId)) {
                ++$error;
            } else {
                ++$deleted;
            }
        }
        return self::deleteFeedbackHandler($deleted, $error);
    }


    /**
     * Handle feedback for the deleteById method
     *
     * @param int $deleted
     * @param int $error
     *
     * @return bool
     */
    public static function deleteFeedbackHandler($deleted, $error) : bool
    {
        if ($deleted > 0 && $error === 0) {
            if ($deleted > 1) {
                Session::add('feedback_positive', 'Er zijn ' . $deleted . ' bijlagen verwijderd.');
            } else {
                Session::add('feedback_positive', 'Er is ' . $deleted . ' bijlage verwijderd.');
            }
            return true;
        }
        if ($deleted > 0 && $error > 0) {
            Session::add('feedback_warning', 'Aantal bijlagen verwijderd: ' . $deleted . '. Aantal bijlagen met problemen: ' . $error);
            return true;
        }
        Session::add('feedback_negative', 'Verwijderen mislukt. Aantal bijlagen met problemen: ' . $error);
        return false;
    }
}
