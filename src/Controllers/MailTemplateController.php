<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachment;
use PortalCMS\Core\Email\Template\EmailTemplateManager;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;

/**
 * MailTemplateController
 * Controls everything mail-template-related
 */
class MailTemplateController extends Controller
{
    /**
     * The requests that this controller will handle
     * @var array $requests
     */
    private $requests = [
        'setYear' => 'POST',
        'uploadAttachment' => 'POST',
        'deleteMailTemplateAttachments' => 'POST',
        'deleteTemplate' => 'POST',
        'newTempalte' => 'POST',
        'editTemplate' => 'POST'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        Router::processRequests($this->requests, __CLASS__);
    }

    public static function deleteTemplate() : void
    {
        EmailTemplateManager::delete((int) Request::post('id'));
        Redirect::to('email/templates/');
    }

    public static function deleteMailTemplateAttachments() : void
    {
        EmailAttachment::deleteById(Request::post('id'));
        Redirect::to('email/templates/edit.php?id=' . Request::get('id'));
    }

    public static function uploadAttachment() : void
    {
        Authentication::checkAuthentication();
        $attachment = new EmailAttachment($_FILES['attachment_file']);
        $attachment->store(null, (int) Request::get('id'));
        Redirect::to('email/templates/edit.php?id=' . Request::get('id'));
    }

    public static function editTemplate() : void
    {
        $templateBuilder = new EmailTemplateManager();
        $template = $templateBuilder->getExisting((int) Request::get('id'));
        $template->subject = Request::post('subject', true);
        $template->body = Request::post('body', false);
        $templateBuilder->update($template);
        Redirect::to('email/templates/');
    }

    public static function newTemplate() : void
    {
        Authentication::checkAuthentication();
        $templateBuilder = new EmailTemplateManager();
        $templateBuilder->create('member', Request::post('subject', true), Request::post('body', false));
        $templateBuilder->store();
        Redirect::to('email/templates/');
    }

    public static function setYear() : void
    {
        $year = Request::post('year');
        header('Location: '.$_SERVER['PHP_SELF'].'?year='.$year);
    }
}
