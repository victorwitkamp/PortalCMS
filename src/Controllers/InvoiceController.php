<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Modules\Invoices\InvoiceModel;
use PortalCMS\Modules\Invoices\InvoiceItemModel;

/**
 * InvoiceController
 * Controls everything that is invoice-related
 */
class InvoiceController extends Controller
{
    private $requests = [
        'createInvoiceMail' => 'POST',
        'writeInvoice' => 'POST',
        'createInvoice' => 'POST',
        'deleteInvoice' => 'POST',
        'deleteInvoiceItem' => 'POST',
        'addInvoiceItem' => 'POST'
    ];

    public function __construct()
    {
        parent::__construct();
        Router::processRequests($this->requests, __CLASS__);
    }

    public static function createInvoiceMail()
    {
        if (InvoiceModel::createMail()) {
            Redirect::to('rental/invoices');
        } else {
            Redirect::to('includes/error.php');
        }
    }

    public static function writeInvoice()
    {
        $id = Request::post('id', true);
        if (!InvoiceModel::write($id)) {
            Redirect::to('includes/error.php');
        }
    }
    public static function createInvoice()
    {
        $year = Request::post('year', true);
        $month = Request::post('month', true);
        $contracts = Request::post('contract_id', false);
        if (InvoiceModel::create($year, $month, $contracts)) {
            Redirect::to('rental/invoices');
        } else {
            Redirect::to('includes/error.php');
        }
    }
    public static function deleteInvoice()
    {
        $id = (int) Request::post('id', true);
        if (InvoiceModel::delete($id)) {
            Redirect::to('rental/invoices');
        } else {
            Redirect::to('includes/error.php');
        }
    }
    public static function deleteInvoiceItem()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $id = (int) Request::post('id', true);
        if (InvoiceItemModel::delete($id)) {
            Redirect::to('rental/invoices/details.php?id=' . $invoiceId);
        } else {
            Redirect::to('includes/error.php');
        }
    }
    public static function addInvoiceItem()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $name = Request::post('name', true);
        $price = (int) Request::post('price', true);
        if (InvoiceItemModel::create($invoiceId, $name, $price)) {
            Redirect::to('rental/invoices/details.php?id=' . $invoiceId);
        } else {
            Redirect::to('includes/error.php');
        }
    }
}
