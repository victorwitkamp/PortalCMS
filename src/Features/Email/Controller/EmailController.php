<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\Http\RequestInputMapper;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Email\Batch\MailBatch;
use PortalCMS\Features\Email\Entity\MailSchedule as ScheduledMail;
use PortalCMS\Features\Email\Entity\MailTemplate as TemplateEntity;
use PortalCMS\Features\Email\Input\MailTemplateInput;
use PortalCMS\Features\Email\Input\ScheduleMemberMailInput;
use PortalCMS\Features\Email\Repository\MailBatchRepository;
use PortalCMS\Features\Email\Repository\MailScheduleRepository;
use PortalCMS\Features\Email\Repository\MailTemplateRepository;
use PortalCMS\Features\Email\Schedule\MailSchedule;
use PortalCMS\Features\Email\Template\MailTemplate;
use PortalCMS\Features\Members\Repository\MemberRepository;
use PortalCMS\Features\Users\Authorization\Authorization;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class EmailController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly RequestInputMapper $inputMapper,
        private readonly MailScheduleRepository $mails,
        private readonly MailBatchRepository $batches,
        private readonly MailTemplateRepository $mailTemplates,
        private readonly MemberRepository $members,
        private readonly MailSchedule $schedule,
        private readonly MailBatch $batch,
        private readonly MailTemplate $template,
        private readonly Authorization $authorization,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Email/Batches', name: 'email.batches', methods: [ 'GET' ])]
    #[Route('/Email/Batches/', name: 'email.batches_slash', methods: [ 'GET' ])]
    public function batches(): Response
    {
        if (!$this->canSchedule()) {
            return $this->forbiddenResponse();
        }

        $batches = $this->batches->findAllOrdered();
        $messageCounts = [];
        foreach ($batches as $batch) {
            $messageCounts[$batch->id] = $this->batches->countMessages($batch->id);
        }

        return $this->render('Email::MailBatchListPage', compact('batches', 'messageCounts'));
    }

    #[Route('/Email/Messages', name: 'email.messages', methods: [ 'GET' ])]
    #[Route('/Email/Messages/', name: 'email.messages_slash', methods: [ 'GET' ])]
    public function messages(Request $request): Response
    {
        if (!$this->canSchedule()) {
            return $this->forbiddenResponse();
        }

        $batchId = $request->query->getInt('batch_id') ?: null;
        $mails = $batchId === null
            ? $this->mails->findAllOrdered()
            : $this->mails->findByBatchId($batchId);

        return $this->render('Email::ScheduledMailListPage', compact('mails', 'batchId'));
    }

    #[Route('/Email/History', name: 'email.history', methods: [ 'GET' ])]
    public function history(): Response
    {
        return $this->canSchedule()
            ? $this->render('Email::MailHistoryPage', [ 'mails' => $this->mails->findHistory() ])
            : $this->forbiddenResponse();
    }

    #[Route('/Email/Details', name: 'email.details', methods: [ 'GET' ])]
    public function details(Request $request): Response
    {
        if (!$this->canSchedule()) {
            return $this->forbiddenResponse();
        }
        $mail = $this->mails->find($request->query->getInt('id'));
        if (!$mail instanceof ScheduledMail) {
            return $this->notFoundResponse();
        }

        return $this->render('Email::ScheduledMailDetailsPage', [ 'mail' => $mail ]);
    }

    #[Route('/Email/ViewTemplates', name: 'email.templates', methods: [ 'GET' ])]
    public function viewTemplates(): Response
    {
        return $this->canEditTemplates()
            ? $this->render('Email::MailTemplateListPage', [
                'mailTemplates' => $this->mailTemplates->findAllOrdered(),
            ])
            : $this->forbiddenResponse();
    }

    #[Route('/Email/EditTemplate', name: 'email.template_edit', methods: [ 'GET' ])]
    public function editTemplate(Request $request): Response
    {
        if (!$this->canEditTemplates()) {
            return $this->forbiddenResponse();
        }
        $template = $this->mailTemplates->find($request->query->getInt('id'));
        if (!$template instanceof TemplateEntity) {
            return $this->notFoundResponse();
        }

        return $this->render('Email::EditMailTemplatePage', [ 'mailTemplate' => $template ]);
    }

    #[Route('/Email/NewTemplate', name: 'email.template_new', methods: [ 'GET' ])]
    public function newTemplate(): Response
    {
        return $this->canEditTemplates()
            ? $this->render('Email::CreateMailTemplatePage')
            : $this->forbiddenResponse();
    }

    #[Route('/Email/Generate', name: 'email.generate', methods: [ 'GET' ])]
    #[Route('/Email/Generate/', name: 'email.generate_slash', methods: [ 'GET' ])]
    public function generate(): Response
    {
        return $this->canSchedule()
            ? $this->render('Email::ComposeMailPage')
            : $this->forbiddenResponse();
    }

    #[Route('/Email/GenerateMember', name: 'email.generate_member', methods: [ 'GET' ])]
    #[Route('/Email/GenerateMember/', name: 'email.generate_member_slash', methods: [ 'GET' ])]
    public function generateMember(Request $request): Response
    {
        if (!$this->canSchedule()) {
            return $this->forbiddenResponse();
        }
        $year = $request->query->getInt('year') ?: (int) date('Y');

        return $this->render('Email::ComposeMemberMailPage', [
            'year' => $year,
            'members' => $this->members->findRows($year),
            'mailTemplates' => $this->mailTemplates->findByType('member'),
        ]);
    }

    #[Route('/Email/GenerateMember/Year', name: 'email.generate_member_year', methods: [ 'POST' ])]
    public function selectMemberYear(Request $request): Response
    {
        return $this->redirectToRoute('email.generate_member', [
            'year' => $request->request->getInt('year'),
        ]);
    }

    #[Route('/Email/GenerateMember', name: 'email.schedule_members', methods: [ 'POST' ])]
    public function scheduleMembers(Request $request): Response
    {
        if (!$this->canSchedule()) {
            return $this->forbiddenResponse();
        }
        /** @var ScheduleMemberMailInput $input */
        $input = $this->inputMapper->map($request, ScheduleMemberMailInput::class);
        $result = $this->schedule->createFromMemberTemplate(
            $input->templateid,
            $input->recipients,
            (int) $this->session()->get('user_id'),
        );
        if ($result['created'] > 0) {
            $this->addFlash('success', 'Totaal aantal berichten aangemaakt: ' . $result['created']);
        }
        if ($result['failed'] > 0) {
            $this->addFlash('warning', 'Berichten met fout: ' . $result['failed']);
        }

        return $this->redirectToRoute('email.messages');
    }

    #[Route('/Email/NewTemplate', name: 'email.template_create', methods: [ 'POST' ])]
    public function createTemplate(Request $request): Response
    {
        if (!$this->canEditTemplates()) {
            return $this->forbiddenResponse();
        }
        /** @var MailTemplateInput $input */
        $input = $this->inputMapper->map($request, MailTemplateInput::class);
        $created = $this->template->create(
            'member',
            trim($input->subject),
            $input->body,
            (int) $this->session()->get('user_id'),
        );
        $this->addFlash(
            $created === null ? 'danger' : 'success',
            $created === null ? 'Nieuwe template aanmaken mislukt.' : 'Template toegevoegd (ID = ' . $created->id . ')',
        );

        return $this->redirectToRoute('email.templates');
    }

    #[Route('/Email/EditTemplate', name: 'email.template_update', methods: [ 'POST' ])]
    public function updateTemplate(Request $request): Response
    {
        if (!$this->canEditTemplates()) {
            return $this->forbiddenResponse();
        }
        $template = $this->mailTemplates->find($request->request->getInt('id'));
        if (!$template instanceof TemplateEntity) {
            return $this->notFoundResponse();
        }

        /** @var MailTemplateInput $input */
        $input = $this->inputMapper->map($request, MailTemplateInput::class);
        $updated = $this->template->update(
            $template,
            trim($input->subject),
            $input->body,
        );
        $this->addFlash(
            $updated ? 'success' : 'danger',
            $updated ? 'Template opgeslagen.' : 'Niet alle velden zijn ingevuld.',
        );

        return $this->redirectToRoute('email.templates');
    }

    #[Route('/Email/ViewTemplates/Delete', name: 'email.template_delete', methods: [ 'POST' ])]
    public function deleteTemplate(Request $request): Response
    {
        if (!$this->canEditTemplates()) {
            return $this->forbiddenResponse();
        }
        $deleted = $this->template->delete($request->request->getInt('id'));
        $this->addFlash(
            $deleted ? 'success' : 'danger',
            $deleted ? 'Template verwijderd.' : 'Verwijderen van template mislukt.',
        );

        return $this->redirectToRoute('email.templates');
    }

    #[Route('/Email/EditTemplate/Attachment', name: 'email.template_attachment_upload', methods: [ 'POST' ])]
    public function uploadAttachment(Request $request): Response
    {
        if (!$this->canEditTemplates()) {
            return $this->forbiddenResponse();
        }
        $template = $this->mailTemplates->find($request->request->getInt('template_id'));
        if (!$template instanceof TemplateEntity) {
            return $this->notFoundResponse();
        }

        $uploaded = $this->template->uploadAttachment(
            $template,
            $request->files->get('attachment_file'),
        );
        $this->addFlash(
            $uploaded ? 'success' : 'danger',
            $uploaded ? 'Bijlage geupload.' : 'Uploaden van bijlage mislukt.',
        );

        return $this->redirectToRoute('email.template_edit', [ 'id' => $template->id ]);
    }

    #[Route('/Email/EditTemplate/Attachments/Delete', name: 'email.template_attachments_delete', methods: [ 'POST' ])]
    public function deleteAttachments(Request $request): Response
    {
        if (!$this->canEditTemplates()) {
            return $this->forbiddenResponse();
        }
        $template = $this->mailTemplates->find($request->request->getInt('template_id'));
        if (!$template instanceof TemplateEntity) {
            return $this->notFoundResponse();
        }
        $result = $this->template->deleteAttachments($template, $this->ids($request));
        $this->addFlash(
            $result['deleted'] > 0 ? 'success' : 'danger',
            'Aantal bijlagen verwijderd: ' . $result['deleted'] . '. Problemen: ' . $result['failed'],
        );

        return $this->redirectToRoute('email.template_edit', [ 'id' => $template->id ]);
    }

    #[Route('/Email/Messages/Send', name: 'email.messages_send', methods: [ 'POST' ])]
    public function sendMessages(Request $request): Response
    {
        if (!$this->canSchedule()) {
            return $this->forbiddenResponse();
        }
        $this->addSendFeedback($this->schedule->send($this->ids($request)));

        return $this->redirectToRoute('email.messages');
    }

    #[Route('/Email/Messages/Delete', name: 'email.messages_delete', methods: [ 'POST' ])]
    public function deleteMessages(Request $request): Response
    {
        if (!$this->canSchedule()) {
            return $this->forbiddenResponse();
        }
        $result = $this->schedule->delete($this->ids($request));
        $this->addFlash(
            $result['deleted'] > 0 ? 'success' : 'danger',
            'Berichten verwijderd: ' . $result['deleted'] . '. Problemen: ' . $result['failed'],
        );

        return $this->redirectToRoute('email.messages');
    }

    #[Route('/Email/Batches/Send', name: 'email.batches_send', methods: [ 'POST' ])]
    public function sendBatches(Request $request): Response
    {
        if (!$this->canSchedule()) {
            return $this->forbiddenResponse();
        }
        $this->addSendFeedback($this->batch->send($this->ids($request)));

        return $this->redirectToRoute('email.batches');
    }

    #[Route('/Email/Batches/Delete', name: 'email.batches_delete', methods: [ 'POST' ])]
    public function deleteBatches(Request $request): Response
    {
        if (!$this->canSchedule()) {
            return $this->forbiddenResponse();
        }
        $result = $this->batch->delete($this->ids($request));
        $this->addFlash(
            $result['batches'] > 0 ? 'success' : 'danger',
            'Batches verwijderd: ' . $result['batches']
            . '. Berichten verwijderd: ' . $result['messages']
            . '. Problemen: ' . $result['failed'],
        );

        return $this->redirectToRoute('email.batches');
    }

    /** @return int[] */
    private function ids(Request $request, string $field = 'id'): array
    {
        $ids = $request->request->all($field);
        return array_values(array_filter(
            array_map('intval', is_array($ids) ? $ids : []),
            static fn (int $id): bool => $id > 0,
        ));
    }

    /** @param array{sent: int, failed: int, alreadySent: int} $result */
    private function addSendFeedback(array $result): void
    {
        if ($result['sent'] > 0) {
            $this->addFlash('success', $result['sent'] . ' bericht(en) succesvol verstuurd.');
        }
        if ($result['failed'] > 0) {
            $this->addFlash('danger', $result['failed'] . ' bericht(en) mislukt.');
        }
        if ($result['alreadySent'] > 0) {
            $this->addFlash('warning', $result['alreadySent'] . ' bericht(en) reeds verstuurd.');
        }
        if (array_sum($result) === 0) {
            $this->addFlash('danger', 'Invalid request.');
        }
    }

    private function canSchedule(): bool
    {
        return $this->authorization->hasPermission('mail-scheduler');
    }

    private function canEditTemplates(): bool
    {
        return $this->authorization->hasPermission('mail-templates');
    }
}
