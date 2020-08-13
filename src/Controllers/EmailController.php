<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachment;
use PortalCMS\Core\Email\Schedule\MailSchedule;
use PortalCMS\Core\Email\Template\EmailTemplateManager;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;

class EmailController extends Controller
{
    private $requests = [
        'generateMemberSetYear'         => 'POST',
        'uploadAttachment'              => 'POST',
        'deleteMailTemplateAttachments' => 'POST',
        'deleteTemplate'                => 'POST',
        'addTemplate'                   => 'POST',
        'editTemplateAction'            => 'POST',
        'sendScheduledMailById'         => 'POST',
        'createMailWithTemplate'        => 'POST',
        'deleteScheduledMailById'       => 'POST',
        'sendBatchById'                 => 'POST',
        'deleteBatchById'               => 'POST'
    ];

    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    public static function generateMemberSetYear(): void
    {
        Redirect::to('Email/GenerateMember/?year=' . Request::post('year'));
    }

    public static function uploadAttachment(): void
    {
        Authentication::checkAuthentication();
        $attachment = new EmailAttachment($_FILES['attachment_file']);
        $attachment->store(null, (int) Request::get('id'));
        Redirect::to('email/EditTemplate?id=' . Request::get('id'));
    }

    public static function deleteMailTemplateAttachments(): void
    {
        EmailAttachment::deleteById((int) Request::post('id'));
        Redirect::to('email/EditTemplate?id=' . Request::get('id'));
    }

    public static function deleteTemplate(): void
    {
        EmailTemplateManager::delete((int) Request::post('id'));
        Redirect::to('email/ViewTemplates');
    }

    public static function addTemplate(): void
    {
        Authentication::checkAuthentication();
        $templateBuilder = new EmailTemplateManager();
        $templateBuilder->create('member', Request::post('subject', true), Request::post('body'));
        $templateBuilder->store();
        Redirect::to('email/ViewTemplates');
    }

    public static function editTemplateAction(): void
    {
        $templateBuilder = new EmailTemplateManager();
        $template = $templateBuilder->getExisting((int) Request::get('id'));
        $template->subject = Request::post('subject', true);
        $template->body = Request::post('body');
        $templateBuilder->update($template);
        Redirect::to('email/ViewTemplates');
    }

    public static function sendScheduledMailById(): void
    {
        MailSchedule::sendMailsById((array) Request::post('id'));
        Redirect::to('Email/Messages');
    }

    public static function createMailWithTemplate(): void
    {
        $templateId = filter_input(INPUT_POST, 'templateid', FILTER_VALIDATE_INT);
        $recipients = (array) Request::post('recipients');
        MailSchedule::createWithTemplate($templateId, $recipients);
        Redirect::to('Email/Messages');
    }

    public static function deleteScheduledMailById(): void
    {
        MailSchedule::deleteById((array) Request::post('id'));
        Redirect::to('Email/Messages');
    }

    public static function sendBatchById(): void
    {
        MailBatch::sendById((array) Request::post('id'));
    }

    public static function deleteBatchById(): void
    {
        MailBatch::deleteById((array) Request::post('id'));
    }

    /**
     * Route: Batches.
     */
    public function batches()
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/Batches');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: Messages.
     */
    public function messages()
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/Messages');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: History.
     */
    public function history()
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/History');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: Details.
     */
    public function details()
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/Details');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: ViewTemplates.
     */
    public function viewTemplates()
    {
        if (Authorization::hasPermission('mail-templates')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/ViewTemplates');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: EditTemplates.
     */
    public function editTemplate()
    {
        if (Authorization::hasPermission('mail-templates')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/EditTemplate');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: NewTemplates.
     */
    public function newTemplate()
    {
        if (Authorization::hasPermission('mail-templates')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/NewTemplate');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: NewTemplates.
     */
    public function generate()
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/Generate');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: NewTemplates.
     */
    public function generateMember()
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/GenerateMember');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }
}
