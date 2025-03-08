<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Email\Batch\MailBatch;
use App\Core\Email\Message\Attachment\EmailAttachment;
use App\Core\Email\Schedule\MailSchedule;
use App\Core\Email\Template\EmailTemplateManager;
use App\Core\Security\Authentication\Authentication;
use App\Core\Security\Authorization\Authorization;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Email", name="email")
 */
class EmailController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();
    }

    /**
     * @Route("/Batches", name="batches")
     */
    public function batches() : Response
    {
        if (Authorization::hasPermission('mail-scheduler')) {

            return $this->render('Pages/Email/Batches');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Messages", name="messages")
     */
    public function messages() : Response
    {
        if (Authorization::hasPermission('mail-scheduler')) {

            return $this->render('Pages/Email/Messages');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/History", name="history")
     */
    public function history() : Response
    {
        if (Authorization::hasPermission('mail-scheduler')) {

            return $this->render('Pages/Email/History');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Details", name="details")
     */
    public function details() : Response
    {
        if (Authorization::hasPermission('mail-scheduler')) {

            return $this->render('Pages/Email/Details');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/ViewTemplates", name="viewtemplates")
     */
    public function viewTemplates() : Response
    {
        if (Authorization::hasPermission('mail-templates')) {

            return $this->render('Pages/Email/ViewTemplates');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/EditTemplate", name="edittemplate")
     */
    public function editTemplate() : Response
    {
        if (Authorization::hasPermission('mail-templates')) {

            return $this->render('Pages/Email/EditTemplate');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/NewTemplates", name="newtemplates")
     */
    public function newTemplate() : Response
    {
        if (Authorization::hasPermission('mail-templates')) {

            return $this->render('Pages/Email/NewTemplate');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Generate", name="generate")
     */
    public function generate() : Response
    {
        if (Authorization::hasPermission('mail-scheduler')) {

            return $this->render('Pages/Email/Generate');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/GenerateMember", name="generatemember")
     */
    public function generateMember() : Response
    {
        if (Authorization::hasPermission('mail-scheduler')) {

            return $this->render('Pages/Email/GenerateMember');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    public function generateMemberSetYear(): Response
    {
        return $this->redirectToRoute('Email/GenerateMember/?year=' . $this->request->get('year'));
    }

    public function uploadAttachment(): Response
    {
        Authentication::checkAuthentication();
        $attachment = new EmailAttachment($_FILES['attachment_file']);
        $attachment->store(null, (int)$this->request->get('id'));
        return $this->redirectToRoute('email/EditTemplate?id=' . $this->request->get('id'));
    }

    public function deleteMailTemplateAttachments(): Response
    {
        EmailAttachment::deleteById($this->request->get('id'));
        return $this->redirectToRoute('email/EditTemplate?id=' . $this->request->get('id'));
    }

    public function deleteTemplate(): Response
    {
        EmailTemplateManager::delete((int)$this->request->get('id'));
        return $this->redirectToRoute('emailviewtemplates');
    }

    public function addTemplate(): Response
    {
        Authentication::checkAuthentication();
        $templateBuilder = new EmailTemplateManager();
        $templateBuilder->create('member', $this->request->get('subject'), $this->request->get('body'));
        $templateBuilder->store();
        return $this->redirectToRoute('emailviewtemplates');
    }

    public function editTemplateAction(): Response
    {
        $templateBuilder = new EmailTemplateManager();
        $template = $templateBuilder->getExisting((int)$this->request->get('id'));
        $template->subject = $this->request->get('subject');
        $template->body = $this->request->get('body');
        $templateBuilder->update($template);
        return $this->redirectToRoute('emailviewtemplates');
    }

    public function sendScheduledMailById(): Response
    {
        MailSchedule::sendMailsById((array)$this->request->get('id'));
        return $this->redirectToRoute('emailmessages');
    }

    public function createMailWithTemplate(): Response
    {
        $templateId = filter_input(INPUT_POST, 'templateid', FILTER_VALIDATE_INT);
        $recipients = (array)$this->request->get('recipients');
        MailSchedule::createWithTemplate($templateId, $recipients);
        return $this->redirectToRoute('emailmessages');
    }

    public function deleteScheduledMailById(): Response
    {
        MailSchedule::deleteById((array)$this->request->get('id'));
        return $this->redirectToRoute('emailmessages');
    }

    public function sendBatchById() : Response
    {
        MailBatch::sendById((array)$this->request->get('id'));
    }

    public function deleteBatchById() : Response
    {
        MailBatch::deleteById((array)$this->request->get('id'));
    }
}
