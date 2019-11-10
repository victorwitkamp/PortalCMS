<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Email\Template\EmailTemplateManager;
use PortalCMS\Core\Email\Template\EmailTemplatePDOWriter;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachment;

/**
 * MailTemplateController
 * Controls everything mail-template-related
 */
class MailTemplateController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['newtemplate'])) {
            self::newTemplate();
        }
        if (isset($_POST['setYear'])) {
            self::setYear();
        }
        if (isset($_POST['edittemplate'])) {
            self::editTemplate();
        }
        if (isset($_POST['uploadAttachment'])) {
            self::uploadAttachment();
        }
        if (isset($_POST['deleteMailTemplateAttachments'])) {
            self::deleteMailTemplateAttachments();
        }
        if (isset($_POST['deleteTemplate'])) {
            self::deleteTemplate():
        }
    }

    private static function deleteTemplate() : void
    {
        EmailTemplateManager::delete((int) Request::post('id'));
        Redirect::to('mail/templates/');
    }

    private static function deleteMailTemplateAttachments() : void
    {
        EmailAttachment::deleteById(Request::post('id'));
        Redirect::to('mail/templates/edit.php?id=' . Request::get('id'));
    }

    private static function uploadAttachment() : void
    {
        Authentication::checkAuthentication();
        $attachment = new EmailAttachment($_FILES['attachment_file']);
        $attachment->store(null, (int) Request::get('id'));
        Redirect::to('mail/templates/edit.php?id=' . Request::get('id'));
    }

    private static function editTemplate() : void
    {
        $templateBuilder = new EmailTemplateManager();
        $template = $templateBuilder->getExisting((int) Request::get('id'));
        $template->subject = Request::post('subject', true);
        $template->body = Request::post('body', false);
        $templateBuilder->update($template);
        Redirect::to('mail/templates/');
    }

    private static function newTemplate() : void
    {
        Authentication::checkAuthentication();
        $templateBuilder = new EmailTemplateManager();
        $templateBuilder->create('member', Request::post('subject', true), Request::post('body', false));
        $templateBuilder->store();
        Redirect::to('mail/templates/');
    }

    private static function setYear() : void
    {
        $year = Request::post('year');
        header('Location: '.$_SERVER['PHP_SELF'].'?year='.$year);
    }
}
