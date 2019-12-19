<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Invoices\InvoiceItemModel;
use PortalCMS\Modules\Invoices\InvoiceModel;

/**
 * InvoicesController
 */
class InvoicesController extends Controller
{
    /**
     * The requests that this controller will handle
     * @var array $requests
     */
    private $requests = [
        'createInvoiceMail' => 'POST',
        'writeInvoice' => 'POST',
        'createInvoice' => 'POST',
        'deleteInvoice' => 'POST',
        'deleteInvoiceItem' => 'POST',
        'addInvoiceItem' => 'POST'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    public function index()
    {
        if (Authorization::verifyPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function add()
    {
        if (Authorization::verifyPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/add');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function details()
    {
        if (Authorization::verifyPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/details');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function createPDF()
    {
        if (Authorization::verifyPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/createPDF');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public static function createInvoiceMail()
    {
        $invoiceIds = Request::post('id');
        if (!empty($invoiceIds)) {
            MailBatch::create();
            $batchId = MailBatch::lastInsertedId();
            Session::add('feedback_positive', 'Nieuwe batch aangemaakt (batch ID: ' . $batchId . '). <a href="email/Messages?batch_id=' . $batchId . '">Batch bekijken</a>');
            foreach ($invoiceIds as $invoiceId) {
                InvoiceModel::createMail($invoiceId, $batchId);
            }
            Redirect::to('Invoices');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function writeInvoice()
    {
        $ids = Request::post('writeInvoiceId');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                InvoiceModel::write((int) $id);
            }
            Redirect::to('Invoices');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function createInvoice()
    {
        $year = (int) Request::post('year', true);
        $month = (int) Request::post('month', true);
        $contracts = Request::post('contract_id');
        $factuurdatum = Request::post('factuurdatum', true);
        if (InvoiceModel::create($year, $month, $contracts, $factuurdatum)) {
            Session::add('feedback_positive', 'Factuur toegevoegd.');
            Redirect::to('Invoices');
        }
    }

    public static function deleteInvoice()
    {
        $id = (int) Request::post('id', true);
        if (InvoiceModel::delete($id)) {
            Redirect::to('Invoices/Index');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function deleteInvoiceItem()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $id = (int) Request::post('id', true);
        if (InvoiceItemModel::delete($id)) {
            Redirect::to('Invoices/details?id=' . $invoiceId);
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function addInvoiceItem()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $name = Request::post('name', true);
        $price = (int) Request::post('price', true);
        if (InvoiceItemModel::create($invoiceId, $name, $price)) {
            Redirect::to('Invoices/details?id=' . $invoiceId);
        } else {
            Redirect::to('Error/Error');
        }
    }
}
