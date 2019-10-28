<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Modules\Invoices\InvoiceModel;
use PortalCMS\Modules\Invoices\InvoiceItemModel;

/**
 * InvoiceController
 * Controls everything that is invoice-related
 */
class InvoiceController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['createInvoiceMail'])) {
            self::createInvoiceMail();
        }
        if (isset($_POST['writeInvoice'])) {
            self::writeInvoiceHandler();
        }
        if (isset($_POST['createInvoice'])) {
            self::createInvoiceHandler();
        }
        if (isset($_POST['deleteInvoice'])) {
            self::deleteInvoiceHandler();
        }
        if (isset($_POST['deleteInvoiceItem'])) {
            self::deleteItemHandler();
        }
        if (isset($_POST['addInvoiceItem'])) {
            self::addItemHandler();
        }
    }

    public static function createInvoiceMail()
    {
        if (InvoiceModel::createMail()) {
            Redirect::invoices();
        } else {
            Redirect::error;
        }
    }

    public static function writeInvoiceHandler()
    {
        $id = Request::post('id', true);
        if (!InvoiceModel::write($id)) {
            Redirect::error();
        }
    }
    public static function createInvoiceHandler()
    {
        $year = Request::post('year', true);
        $month = Request::post('month', true);
        $contracts = Request::post('contract_id', false);
        if (InvoiceModel::create($year, $month, $contracts)) {
            Redirect::to('rental/invoices');
        } else {
            Redirect::error();
        }
    }
    public static function deleteInvoiceHandler()
    {
        $id = (int) Request::post('id', true);
        if (InvoiceModel::delete($id)) {
            Redirect::to('rental/invoices');
        } else {
            Redirect::error();
        }
    }
    public static function deleteItemHandler()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $id = (int) Request::post('id', true);
        if (InvoiceItemModel::delete($id)) {
            Redirect::to('rental/invoices/details.php?id=' . $invoiceId);
        } else {
            Redirect::error();
        }
    }
    public static function addItemHandler()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $name = Request::post('name', true);
        $price = (int) Request::post('price', true);
        if (InvoiceItemModel::create($invoiceId, $name, $price)) {
            Redirect::to('rental/invoices/details.php?id=' . $invoiceId);
        } else {
            Redirect::error();
        }
    }
}
