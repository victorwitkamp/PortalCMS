<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\Email\Attachment\EmailAttachment;
use PortalCMS\Core\Email\Template\MailTemplate;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

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
            EmailAttachment::uploadAttachment();
            Redirect::to('mail/templates/edit.php?id=' . Request::get('id'));
        }
        if (isset($_POST['newtemplate'])) {
            MailTemplate::new();
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
