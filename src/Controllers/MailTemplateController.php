<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\View\Text;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Template\MailTemplate;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachment;
use PortalCMS\Core\Email\Template\Builders\ITemplateBuilder;

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
            $TemplateBuilder = new ITemplateBuilder();
            $TemplateBuilder->addAttachment($_FILES['attachment_file'], Request::get('id'));
            // EmailAttachment::uploadAttachment();
            Redirect::to('mail/templates/edit.php?id=' . Request::get('id'));
        }
        if (isset($_POST['newtemplate'])) {
            $emailMessage = new EmailMessage(
                Request::post('subject', true),
                Request::post('body', false)
            );
            $TemplateBuilder = new ITemplateBuilder();
            $TemplateBuilder->create('member', $emailMessage);
            $TemplateBuilder->store();
            Redirect::to('mail/templates/');
        }
        if (isset($_POST['edittemplate'])) {
            MailTemplate::edit();
        }
        if (isset($_POST['deleteMailTemplateAttachments'])) {
            EmailAttachment::deleteById(Request::post('id'));
            Redirect::to('mail/templates/edit.php?id=' . Request::get('id'));
        }
    }
}
