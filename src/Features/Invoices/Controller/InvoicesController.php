<?php

declare(strict_types=1);

namespace PortalCMS\Features\Invoices\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\Http\RequestInputMapper;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Core\View\Text;
use PortalCMS\Features\Activity\Activity;
use PortalCMS\Features\Contracts\Entity\Contract;
use PortalCMS\Features\Contracts\Repository\ContractRepository;
use PortalCMS\Features\Email\Batch\MailBatch;
use PortalCMS\Features\Email\Entity\MailSchedule as ScheduledMail;
use PortalCMS\Features\Email\Schedule\MailSchedule;
use PortalCMS\Features\Email\Template\MailTemplate;
use PortalCMS\Features\Invoices\Entity\Invoice;
use PortalCMS\Features\Invoices\Factory\InvoiceFactory;
use PortalCMS\Features\Invoices\Input\CreateInvoicesInput;
use PortalCMS\Features\Invoices\Input\InvoiceItemInput;
use PortalCMS\Features\Invoices\Repository\InvoiceRepository;
use PortalCMS\Features\Invoices\View\InvoicePdf;
use PortalCMS\Features\Users\Authorization\Authorization;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class InvoicesController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly InvoiceRepository $invoices,
        private readonly ContractRepository $contracts,
        private readonly InvoiceFactory $factory,
        private readonly InvoicePdf $pdf,
        private readonly RequestInputMapper $inputMapper,
        private readonly MailBatch $mailBatch,
        private readonly MailSchedule $mailSchedule,
        private readonly MailTemplate $mailTemplate,
        private readonly Authorization $authorization,
        private readonly Activity $activity,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Invoices', name: 'invoices.index', methods: [ 'GET' ])]
    #[Route('/Invoices/', name: 'invoices.index_slash', methods: [ 'GET' ])]
    #[Route('/Invoices/Index', name: 'invoices.index_legacy', methods: [ 'GET' ])]
    public function index(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $contractId = $request->query->getInt('contract');
        $year = $request->query->getInt('year') ?: $request->query->getInt('Year');
        $contract = $contractId > 0 ? $this->contracts->find($contractId) : null;
        if ($contractId > 0 && !$contract instanceof Contract) {
            return $this->notFoundResponse();
        }

        if ($contract instanceof Contract) {
            $invoices = $year > 0
                ? $this->invoices->findByContractIdAndYear($contract->id, $year)
                : $this->invoices->findByContractId($contract->id);
            $pageName = Text::get('LABEL_CONTRACT_INVOICES_FOR') . $contract->band_naam;
            if ($year > 0) {
                $pageName .= ' (' . $year . ')';
            }
        } else {
            $invoices = $year > 0
                ? $this->invoices->findByYear($year)
                : $this->invoices->findAllOrdered();
            $pageName = (string) Text::get('TITLE_INVOICES');
        }

        $years = $this->invoices->findYears();
        $yearCounts = [];
        foreach ($years as $availableYear) {
            $yearCounts[$availableYear] = $this->invoices->countByYear($availableYear);
        }
        $mailDates = [];
        foreach ($invoices as $invoice) {
            if ($invoice->mail_id !== null) {
                $mailDates[$invoice->id] = $this->mailSchedule->findDateSent($invoice->mail_id);
            }
        }

        return $this->render('Invoices::InvoiceListPage', [
            'invoices' => $invoices,
            'pageName' => $pageName,
            'selectedYear' => $year,
            'years' => $years,
            'yearCounts' => $yearCounts,
            'invoiceCount' => $this->invoices->countAll(),
            'mailDates' => $mailDates,
        ]);
    }

    #[Route('/Invoices/Add', name: 'invoices.add', methods: [ 'GET' ])]
    public function add(): Response
    {
        return $this->allowed()
            ? $this->render('Invoices::CreateInvoicesPage', [ 'contracts' => $this->contracts->findAllOrdered() ])
            : $this->forbiddenResponse();
    }

    #[Route('/Invoices/Add', name: 'invoices.create', methods: [ 'POST' ])]
    public function create(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        /** @var CreateInvoicesInput $input */
        $input = $this->inputMapper->map($request, CreateInvoicesInput::class);
        $created = [];
        $failed = 0;
        foreach ($input->contract_id as $contractId) {
            $contract = $this->contracts->find((int) $contractId);
            if (!$contract instanceof Contract) {
                ++$failed;
                continue;
            }
            $invoice = $this->factory->createForContract(
                $contract,
                $input->year,
                $input->month,
                $input->factuurdatum,
            );
            if ($this->invoices->findByNumber((string) $invoice->factuurnummer) instanceof Invoice) {
                ++$failed;
                continue;
            }
            $this->invoices->save($invoice);
            $created[] = $invoice;
        }
        if ($created !== []) {
            $this->invoices->flush();
            foreach ($created as $invoice) {
                $this->activity->add(
                    'NewInvoice',
                    (int) $this->session()->get('user_id'),
                    'Factuurnr.: ' . $invoice->factuurnummer,
                );
            }
            $this->addFlash('success', count($created) . ' factuur/facturen toegevoegd.');
        }
        if ($failed > 0) {
            $this->addFlash('warning', $failed . ' factuur/facturen overgeslagen.');
        }

        return $this->redirectToRoute('invoices.index');
    }

    #[Route('/Invoices/Details', name: 'invoices.details', methods: [ 'GET' ])]
    public function details(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }
        $invoice = $this->invoices->find($request->query->getInt('id'));
        if (!$invoice instanceof Invoice || !$invoice->contract instanceof Contract) {
            return $this->notFoundResponse();
        }

        return $this->render('Invoices::InvoiceDetailsPage', [
            'invoice' => $invoice,
            'contract' => $invoice->contract,
        ]);
    }

    #[Route('/Invoices/Delete', name: 'invoices.delete', methods: [ 'POST' ])]
    public function delete(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }
        $invoice = $this->invoices->find($request->request->getInt('id'));
        if (!$invoice instanceof Invoice) {
            return $this->notFoundResponse();
        }
        if ($invoice->hasPdf() && !$this->pdf->remove($invoice)) {
            $this->addFlash('danger', 'Verwijderen van factuur mislukt. PDF kon niet worden verwijderd.');
            return $this->redirectToRoute('invoices.details', [ 'id' => $invoice->id ]);
        }

        $number = $invoice->factuurnummer;
        $this->invoices->remove($invoice);
        $this->invoices->flush();
        $this->activity->add(
            'DeleteInvoice',
            (int) $this->session()->get('user_id'),
            'Factuurnr.: ' . $number,
        );
        $this->addFlash('success', 'Factuur verwijderd.');

        return $this->redirectToRoute('invoices.index');
    }

    #[Route('/Invoices/Items/Add', name: 'invoices.item_add', methods: [ 'POST' ])]
    public function addItem(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }
        $invoice = $this->invoices->find($request->request->getInt('invoiceid'));
        if (!$invoice instanceof Invoice) {
            return $this->notFoundResponse();
        }
        if (!$invoice->isDraft()) {
            $this->addFlash('danger', 'Een definitieve factuur kan niet worden gewijzigd.');
            return $this->redirectToRoute('invoices.details', [ 'id' => $invoice->id ]);
        }

        /** @var InvoiceItemInput $input */
        $input = $this->inputMapper->map($request, InvoiceItemInput::class);
        $invoice->addItem($input->name, $input->price);
        $this->invoices->flush();
        $this->activity->add(
            'AddInvoiceItem',
            (int) $this->session()->get('user_id'),
            'Added item "' . $input->name . '" to invoice with ID = ' . $invoice->id,
        );
        $this->addFlash('success', 'Factuuritem toegevoegd.');

        return $this->redirectToRoute('invoices.details', [ 'id' => $invoice->id ]);
    }

    #[Route('/Invoices/Items/Delete', name: 'invoices.item_delete', methods: [ 'POST' ])]
    public function deleteItem(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }
        $item = $this->invoices->findItem($request->request->getInt('id'));
        if ($item === null) {
            return $this->notFoundResponse();
        }
        $invoice = $item->invoice;
        if (!$invoice->isDraft()) {
            $this->addFlash('danger', 'Een definitieve factuur kan niet worden gewijzigd.');
        } else {
            $invoice->removeItem($item);
            $this->invoices->flush();
            $this->addFlash('success', 'Factuuritem verwijderd.');
        }

        return $this->redirectToRoute('invoices.details', [ 'id' => $invoice->id ]);
    }

    #[Route('/Invoices/CreatePDF', name: 'invoices.pdf', methods: [ 'GET' ])]
    public function renderPdf(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }
        $invoice = $this->invoices->find($request->query->getInt('id'));
        if (!$invoice instanceof Invoice || !$invoice->contract instanceof Contract) {
            return $this->notFoundResponse();
        }

        return new Response(
            $this->pdf->render($invoice, $invoice->contract),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $invoice->factuurnummer . '.pdf"',
            ],
        );
    }

    #[Route('/Invoices/Write', name: 'invoices.write', methods: [ 'POST' ])]
    public function writePdf(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }
        $written = 0;
        $failed = 0;
        foreach ($this->ids($request, 'writeInvoiceId') as $id) {
            $invoice = $this->invoices->find($id);
            if (!$invoice instanceof Invoice || !$invoice->contract instanceof Contract || !$invoice->isDraft()) {
                ++$failed;
                continue;
            }
            try {
                $this->pdf->write($invoice, $invoice->contract);
                $invoice->markPdfWritten();
                ++$written;
            } catch (RuntimeException) {
                ++$failed;
            }
        }
        if ($written > 0) {
            $this->invoices->flush();
            $this->addFlash('success', $written . ' PDF-bestand(en) opgeslagen.');
        }
        if ($failed > 0) {
            $this->addFlash('warning', $failed . ' PDF-bestand(en) niet opgeslagen.');
        }

        return $this->redirectToRoute('invoices.index');
    }

    #[Route('/Invoices/ScheduleMail', name: 'invoices.schedule_mail', methods: [ 'POST' ])]
    public function scheduleMail(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }
        $template = $this->mailTemplate->system('InvoiceMail');
        if ($template === null) {
            $this->addFlash('danger', 'De InvoiceMail-template bestaat niet.');
            return $this->redirectToRoute('invoices.index');
        }

        $selected = [];
        foreach ($this->ids($request) as $id) {
            $invoice = $this->invoices->find($id);
            if (
                $invoice instanceof Invoice
                && $invoice->hasPdf()
                && !$invoice->isMailed()
                && $invoice->contract instanceof Contract
                && !empty($invoice->contract->bandleider_email)
            ) {
                $selected[] = $invoice;
            }
        }
        if ($selected === []) {
            $this->addFlash('danger', 'Geen geldige facturen geselecteerd.');
            return $this->redirectToRoute('invoices.index');
        }

        $batch = $this->mailBatch->create($template->id, (int) $this->session()->get('user_id'));
        $queued = [];
        foreach ($selected as $invoice) {
            $month = Text::get(sprintf('MONTH_%02d', $invoice->month));
            $mail = ScheduledMail::create(
                $batch->id,
                null,
                str_replace('{MAAND}', (string) $month, (string) $template->subject),
                str_replace('{FACTUURNUMMER}', (string) $invoice->factuurnummer, (string) $template->body),
                (int) $this->session()->get('user_id'),
            );
            $mail->addRecipient((string) $invoice->contract->bandleider_email);
            $mail->addAttachment(
                'content/invoices/',
                (string) $invoice->factuurnummer,
                '.pdf',
            );
            $this->mailSchedule->queue($mail);
            $queued[] = [ $invoice, $mail ];
        }
        $this->mailSchedule->flush();
        foreach ($queued as [ $invoice, $mail ]) {
            $invoice->markMailed($mail->id);
        }
        $this->invoices->flush();

        $this->addFlash(
            'success',
            [
                'message' => 'Nieuwe batch aangemaakt (batch ID: ' . $batch->id . ').',
                'link' => [
                    'href' => '/Email/Messages?batch_id=' . $batch->id,
                    'label' => 'Batch bekijken',
                ],
            ],
        );

        return $this->redirectToRoute('invoices.index');
    }

    /** @return int[] */
    private function ids(Request $request, string $field = 'id'): array
    {
        return array_values(array_filter(
            array_map('intval', $request->request->all($field)),
            static fn (int $id): bool => $id > 0,
        ));
    }

    private function allowed(): bool
    {
        return $this->authorization->hasPermission('rental-invoices');
    }
}
