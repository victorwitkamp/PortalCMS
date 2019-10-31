<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Template\EmailTemplate;
use PortalCMS\Core\Email\Template\MailTemplateMapper;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachment;
use PortalCMS\Core\Email\Template\TemplateCreator;

/**
 * MailTemplateController
 * Controls everything mail-template-related
 */
class MailTemplateController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['uploadAttachment'])) {
            $attachment = new EmailAttachment($_FILES['attachment_file']);
            $attachment->store(null, Request::get('id'));
            session_write_close();
            Redirect::to('mail/templates/edit.php?id=' . Request::get('id'));
        }
        if (isset($_POST['newtemplate'])) {
            Authentication::checkAuthentication();
            $EmailMessage = new EmailMessage(Request::post('subject', true), Request::post('body', false));
            $TemplateBuilder = new TemplateCreator();
            $Template = $TemplateBuilder->create('member', $EmailMessage, 1);
            $Template->store(new MailTemplateMapper());
            session_write_close();
            Redirect::to('mail/templates/');
        }
        if (isset($_POST['edittemplate'])) {
            EmailTemplate::edit();
        }
        if (isset($_POST['deleteMailTemplateAttachments'])) {
            EmailAttachment::deleteById(Request::post('id'));
            Redirect::to('mail/templates/edit.php?id=' . Request::get('id'));
        }
    }
}
