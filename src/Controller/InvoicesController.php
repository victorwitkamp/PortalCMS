<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Email\Batch\MailBatch;
use App\Core\HTTP\Request;
use App\Core\Security\Authentication\Authentication;
use App\Core\Security\Authorization\Authorization;
use App\Core\View\Text;
use App\Modules\Contracts\ContractMapper;
use App\Modules\Invoices\InvoiceHelper;
use App\Modules\Invoices\InvoiceMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Invoices", name="invoices")
 */
class InvoicesController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();
    }

    /**
     * @Route("", name="")
     */
    public function index(Request $request)
    {
        $contractId = (int) $this->request->get('contract');
        $year = (int) $this->request->get('year');

        if (!empty($contractId) && is_numeric($contractId)) {
            $contract = ContractMapper::getById($contractId);
            if (empty($contract)) {
                return $this->redirectToRoute('errornotfound');
            }
            if (!empty($year)) {
                $invoices = InvoiceMapper::getByContractIdAndYear($contractId, $year);
                $title = Text::get('LABEL_CONTRACT_INVOICES_FOR').$contract->band_naam;
            } else {
                $invoices = InvoiceMapper::getByContractId($contractId);
                $title = Text::get('LABEL_CONTRACT_INVOICES_FOR').$contract->band_naam.' (voor jaar: '.$year.')';
            }
        } else {
            if (!empty($year)) {
                $invoices = InvoiceMapper::getByYear($year);
            } else {
                $invoices = InvoiceMapper::getAll();
            }
            $title = Text::get('TITLE_INVOICES');
        }
        if (Authorization::hasPermission('rental-invoices')) {

            return $this->render('Pages/Invoices/Index', [ 'title' => $title, 'invoices' => $invoices ]);
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }

    /**
     * @Route("/Add", name="add")
     */
    public function add()
    {
        if (Authorization::hasPermission('rental-invoices')) {
            return $this->render('Pages/Invoices/Add');
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }

    /**
     * @Route("/Details", name="details")
     */
    public function details()
    {
        if (Authorization::hasPermission('rental-invoices')) {
            return $this->render('Pages/Invoices/Details');
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }

    /**
     * @Route("/CreatePDF?id={id}", name="createpdf")
     */
    public function createPDF(int $id) : \Symfony\Component\HttpFoundation\Response
    {
        if (Authorization::hasPermission('rental-invoices')) {
            return new \Symfony\Component\HttpFoundation\Response(InvoiceHelper::render($id));
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }

    public function createInvoiceMail()
    {
        $invoiceIds = $this->request->get('id');
        if (!empty($invoiceIds)) {
            MailBatch::create();
            $batchId = MailBatch::lastInsertedId();
            //todo $this->addFlash('success','Nieuwe batch aangemaakt (batch ID: ' . $batchId . '). <a href="email/Messages?batch_id=' . $batchId . '">Batch bekijken</a>');
            foreach ($invoiceIds as $invoiceId) {
                InvoiceHelper::createMail((int)$invoiceId, (int)$batchId);
            }
            return $this->redirectToRoute('invoices');
        } else {
            return $this->redirectToRoute('error');
        }
    }

    public function writeInvoice()
    {
        $ids = $this->request->get('writeInvoiceId');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                InvoiceHelper::write((int)$id);
            }
            return $this->redirectToRoute('invoices');
        } else {
            return $this->redirectToRoute('error');
        }
    }

    public function createInvoice()
    {
        $year = (int)$this->request->get('year');
        $month = (string)$this->request->get('month');
        $contracts = (array)$this->request->get('contract_id');
        $factuurdatum = (string)$this->request->get('factuurdatum');
        if (InvoiceHelper::create($year, $month, $contracts, $factuurdatum)) {
            //todo $this->addFlash('success','Factuur toegevoegd.');
            return $this->redirectToRoute('invoices');
        }
    }

    public function deleteInvoice()
    {
        if (InvoiceHelper::delete((int)$this->request->get('id'))) {
            return $this->redirectToRoute('/Invoices');
        } else {
            return $this->redirectToRoute('error');
        }
    }

    public function deleteInvoiceItem()
    {
        if (InvoiceHelper::deleteItem((int)$this->request->get('id'))) {
            return $this->redirectToRoute('Invoices/Details?id=' . (int)$this->request->get('invoiceid'));
        } else {
            return $this->redirectToRoute('error');
        }
    }

    public function addInvoiceItem()
    {
        $invoiceId = (int)$this->request->get('invoiceid');
        if (InvoiceHelper::createItem($invoiceId, (string)$this->request->get('name'), (int)$this->request->get('price'))) {
            return $this->redirectToRoute('Invoices/Details?id=' . $invoiceId);
        } else {
            return $this->redirectToRoute('error');
        }
    }

    public function showInvoicesByYear()
    {
        return $this->redirectToRoute('Invoices?Year=' . $this->request->get('year'));
    }
}
