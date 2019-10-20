<?php

use PortalCMS\Core\Config;
use PortalCMS\Core\Redirect;
use PortalCMS\Core\Request;
use PortalCMS\Core\Session;
use PortalCMS\Core\Text;

class MailAttachment
{
    public static function getMIMEType($filename)
    {
        $realpath = realpath($filename);
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $realpath);
    }

    /**
     * Perform the upload of the avatar
     * Authentication::checkAuthentication() makes sure that only logged in users can use this action and see this page
     * POST-request
     */
    public static function uploadAttachment()
    {
        if (self::createAttachment()) {
            return true;
        }
        return false;
    }

    /**
     * Create an avatar picture (and checks all necessary things too)
     * TODO decouple
     * TODO total rebuild
     */
    public static function createAttachment()
    {
        if (!self::isAttachmentFolderWritable()) {
            return false;
        }
        if (!self::validateAttachmentFile()) {
            return false;
        }
        $target_file_path = Config::get('PATH_ATTACHMENTS').$_FILES['attachment_file']['name'];
        move_uploaded_file($_FILES['attachment_file']['tmp_name'], $target_file_path);
        $name = pathinfo($_FILES['attachment_file']['name'], PATHINFO_FILENAME);
        $ext = pathinfo($_FILES['attachment_file']['name'], PATHINFO_EXTENSION);
        $mime = self::getMIMEType($target_file_path);
        MailAttachmentMapper::createForTemplate(Request::get('id'), Config::get('PATH_ATTACHMENTS_PUBLIC'), $name, '.'.$ext, 'base64', $mime);
        return true;
    }

    /**
     * Checks if the avatar folder exists and is writable
     *
     * @return bool success status
     */
    public static function isAttachmentFolderWritable()
    {
        $path_attachment = Config::get('PATH_ATTACHMENTS');
        if (!is_dir(Config::get('PATH_ATTACHMENTS'))) {
            Session::add('feedback_negative', 'Directory '.$path_attachment.' doesnt exist');
            return false;
        }
        if (!is_writable(Config::get('PATH_ATTACHMENTS'))) {
            Session::add('feedback_negative', 'Directory '.$path_attachment.' is not writeable');
            return false;
        }
        return true;
    }

    /**
     * Validates the image
     * TODO totally decouple
     *
     * @return bool
     */
    public static function validateAttachmentFile()
    {
        if (!isset($_FILES['attachment_file'])) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
            return false;
        }
        if ($_FILES['attachment_file']['size'] > 5000000) {
            // if input file too big (>5MB)
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG'));
            return false;
        }
        // get the image width, height and mime type
        // $image_proportions = getimagesize($_FILES['attachment_file']['tmp_name']);
        // if input file too small
        // if ($image_proportions[0] < Config::get('AVATAR_SIZE') or $image_proportions[1] < Config::get('AVATAR_SIZE')) {
        //     Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_SMALL'));
        //     return false;
        // }
        // if (!($image_proportions['mime'] == 'image/jpeg')) {
        //     Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE'));
        //     return false;
        // }
        return true;
    }

    public static function deleteById()
    {
        $mailid = Request::get('id');
        $deleted = 0;
        $error = 0;
        if (!empty($_POST['id'])) {
            foreach ($_POST['id'] as $id) {
                if (!MailAttachmentMapper::deleteById($id)) {
                    $error += 1;
                } else {
                    $deleted += 1;
                }
            }
        }
        if (!$deleted > 0) {
            Session::add('feedback_negative', "Verwijderen mislukt. Aantal berichten met problemen: ".$error);
            return false;
        }
        Session::add('feedback_positive', "Er zijn ".$deleted." berichten verwijderd.");
        Redirect::to('mail/templates/edit.php?id='.$mailid);
    }
}
