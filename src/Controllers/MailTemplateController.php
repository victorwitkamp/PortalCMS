<?php

namespace PortalCMS\Controllers;

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
            // $TemplateBuilder = new TemplateCreator();
            // $TemplateBuilder->addAttachment($_FILES['attachment_file'], Request::get('id'));
            // Redirect::to('mail/templates/edit.php?id=' . Request::get('id'));
        }
        if (isset($_POST['newtemplate'])) {
            $EmailMessage = new EmailMessage(Request::post('subject', true), Request::post('body', false));
            // print_r($EmailMessage);
            // echo '<hr>';
            $TemplateBuilder = new TemplateCreator('member', $EmailMessage);
            // print_r($TemplateBuilder);
            // echo '<hr>';
            // print_r($Template);
            die;
            // $Template->store(new MailTemplateMapper());
            // session_write_close();
            // Redirect::to('mail/templates/');
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
