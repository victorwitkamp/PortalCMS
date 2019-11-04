<?php
declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Email\Template\EmailTemplateBuilder;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Controllers\Controller;
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
            Authentication::checkAuthentication();
            $templateBuilder = new EmailTemplateBuilder();
            $templateBuilder->create('member', Request::post('subject', true), Request::post('body', false));
            $templateBuilder->store();
            Redirect::to('mail/templates/');
        }
        if (isset($_POST['edittemplate'])) {
            $templateBuilder = new EmailTemplateBuilder();
            $template = $templateBuilder->getExisting(Request::get('id'));
            $template->subject = Request::post('subject', true);
            $template->body = Request::post('body', false);
            $templateBuilder->update($template);
            Redirect::to('mail/templates/');
        }
        if (isset($_POST['uploadAttachment'])) {
            Authentication::checkAuthentication();
            $attachment = new EmailAttachment($_FILES['attachment_file']);
            $attachment->store(null, (int) Request::get('id'));
            Redirect::to('mail/templates/edit.php?id=' . Request::get('id'));
        }
        if (isset($_POST['deleteMailTemplateAttachments'])) {
            EmailAttachment::deleteById(Request::post('id'));
            Redirect::to('mail/templates/edit.php?id=' . Request::get('id'));
        }
    }
}
