<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use League\Plates\Engine;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachment;
use PortalCMS\Core\Email\Schedule\MailSchedule;
use PortalCMS\Core\Email\Template\EmailTemplateManager;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use Psr\Http\Message\ResponseInterface;

/**
 * Class EmailController
 * @package PortalCMS\Controllers
 */
class EmailController
{
    protected $templates;

//    private $requests = [
//        'generateMemberSetYear'         => 'POST',
//        'uploadAttachment'              => 'POST',
//        'deleteMailTemplateAttachments' => 'POST',
//        'deleteTemplate'                => 'POST',
//        'addTemplate'                   => 'POST',
//        'editTemplateAction'            => 'POST',
//        'sendScheduledMailById'         => 'POST',
//        'createMailWithTemplate'        => 'POST',
//        'deleteScheduledMailById'       => 'POST',
//        'sendBatchById'                 => 'POST',
//        'deleteBatchById'               => 'POST'
//    ];

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public static function generateMemberSetYear() : ResponseInterface
    {
        return new RedirectResponse('/Email/GenerateMember/?year=' . Request::post('year'));
    }

    public static function uploadAttachment() : ResponseInterface
    {
        Authentication::checkAuthentication();
        $attachment = new EmailAttachment($_FILES['attachment_file']);
        $attachment->store(null, (int) Request::get('id'));
        return new RedirectResponse('/Email/EditTemplate?id=' . Request::get('id'));
    }

    public static function deleteMailTemplateAttachments() : ResponseInterface
    {
        EmailAttachment::deleteById([(int) Request::post('id')]);
        return new RedirectResponse('/Email/EditTemplate?id=' . Request::get('id'));
    }

    public static function deleteTemplate() : ResponseInterface
    {
        EmailTemplateManager::delete((int) Request::post('id'));
        return new RedirectResponse('/Email/ViewTemplates');
    }

    public static function addTemplate() : ResponseInterface
    {
        Authentication::checkAuthentication();
        $templateBuilder = new EmailTemplateManager();
        $body = (string) Request::post('body');
        $subject = (string) Request::post('subject', true);
        if (!empty($body) && $body !== null && !empty($subject) && $subject !== null) {
            $templateBuilder->create('member', $subject, $body);
            $templateBuilder->store();
        }
        return new RedirectResponse('/Email/ViewTemplates');
    }

    public static function editTemplateAction() : ResponseInterface
    {
        $templateBuilder = new EmailTemplateManager();
        $template = $templateBuilder->getExisting((int) Request::get('id'));
        $template->subject = Request::post('subject', true);
        $template->body = Request::post('body');
        $templateBuilder->update($template);
        return new RedirectResponse('/Email/ViewTemplates');
    }

    public static function sendScheduledMailById() : ResponseInterface
    {
        MailSchedule::sendMailsById((array) Request::post('id'));
        return new RedirectResponse('/Email/Messages');
    }

    public static function createMailWithTemplate() : ResponseInterface
    {
        $templateId = filter_input(INPUT_POST, 'templateid', FILTER_VALIDATE_INT);
        $recipients = (array) Request::post('recipients');
        MailSchedule::createWithTemplate($templateId, $recipients);
        return new RedirectResponse('/Email/Messages');
    }

    public static function deleteScheduledMailById() : ResponseInterface
    {
        MailSchedule::deleteById((array) Request::post('id'));
        return new RedirectResponse('/Email/Messages');
    }

    public static function sendBatchById(): void
    {
        MailBatch::sendById((array) Request::post('id'));
    }

    public static function deleteBatchById(): void
    {
        MailBatch::deleteById((array) Request::post('id'));
    }

    public function batches() : ResponseInterface
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            return new HtmlResponse($this->templates->render('Pages/Email/Batches'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function messages() : ResponseInterface
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            return new HtmlResponse($this->templates->render('Pages/Email/Messages'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function history() : ResponseInterface
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            return new HtmlResponse($this->templates->render('Pages/Email/History'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function details() : ResponseInterface
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            return new HtmlResponse($this->templates->render('Pages/Email/Details'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function viewTemplates() : ResponseInterface
    {
        if (Authorization::hasPermission('mail-templates')) {
            return new HtmlResponse($this->templates->render('Pages/Email/ViewTemplates'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function editTemplate() : ResponseInterface
    {
        if (Authorization::hasPermission('mail-templates')) {
            return new HtmlResponse($this->templates->render('Pages/Email/EditTemplate'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function newTemplate() : ResponseInterface
    {
        if (Authorization::hasPermission('mail-templates')) {
            return new HtmlResponse($this->templates->render('Pages/Email/NewTemplate'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function generate() : ResponseInterface
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            return new HtmlResponse($this->templates->render('Pages/Email/Generate'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function generateMember() : ResponseInterface
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            return new HtmlResponse($this->templates->render('Pages/Email/GenerateMember'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }
}
