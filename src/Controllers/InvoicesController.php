<?php
/**
 * Copyright Victor Witkamp (c) 2020.
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
use PortalCMS\Modules\Invoices\InvoiceHelper;

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
        if (Authorization::hasPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function add()
    {
        if (Authorization::hasPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/Add');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function details()
    {
        if (Authorization::hasPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/Details');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function createPDF()
    {
        if (Authorization::hasPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/CreatePDF');
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
                InvoiceHelper::createMail((int) $invoiceId, (int) $batchId);
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
                InvoiceHelper::write((int) $id);
            }
            Redirect::to('Invoices');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function createInvoice()
    {
        $year = (int) Request::post('year', true);
        $month = (string) Request::post('month', true);
        $contracts = (array) Request::post('contract_id');
        $factuurdatum = (string) Request::post('factuurdatum', true);
        if (InvoiceHelper::create($year, $month, $contracts, $factuurdatum)) {
            Session::add('feedback_positive', 'Factuur toegevoegd.');
            Redirect::to('Invoices');
        }
    }

    public static function deleteInvoice()
    {
        $id = (int) Request::post('id', true);
        if (InvoiceHelper::delete($id)) {
            Redirect::to('Invoices/Index');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function deleteInvoiceItem()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $id = (int) Request::post('id', true);
        if (InvoiceHelper::deleteItem($id)) {
            Redirect::to('Invoices/Details?id=' . $invoiceId);
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function addInvoiceItem()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $name = (string) Request::post('name', true);
        $price = (int) Request::post('price', true);
        if (InvoiceHelper::createItem($invoiceId, $name, $price)) {
            Redirect::to('Invoices/Details?id=' . $invoiceId);
        } else {
            Redirect::to('Error/Error');
        }
    }
}
