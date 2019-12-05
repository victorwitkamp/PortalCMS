<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
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
        Router::processRequests($this->requests, __CLASS__);
    }

    public function index()
    {
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Invoices/index');
    }

    public static function createInvoiceMail()
    {
        $invoiceIds = Request::post('id');
        if (!empty($invoiceIds)) {
            MailBatch::create();
            $batchId = MailBatch::lastInsertedId();
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
        $year = Request::post('year', true);
        $month = Request::post('month', true);
        $contracts = Request::post('contract_id');
        if (InvoiceModel::create($year, $month, $contracts)) {
            Redirect::to('Invoices');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function deleteInvoice()
    {
        $id = (int) Request::post('id', true);
        if (InvoiceModel::delete($id)) {
            Redirect::to('Invoices');
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
