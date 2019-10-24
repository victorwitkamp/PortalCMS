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
            InvoiceModel::createMail();
        }
        if (isset($_POST['writeInvoice'])) {
            $id = Request::post('id', true);
            if (!InvoiceModel::write($id)) {
                Redirect::error();
            }
        }
        if (isset($_POST['createInvoice'])) {
            $year = Request::post('year', true);
            $month = Request::post('month', true);
            $contracts = Request::post('contract_id', true);
            if (InvoiceModel::create($year, $month, $contracts)) {
                Redirect::to('rental/invoices/index.php');
            } else {
                Redirect::error();
            }
        }
        if (isset($_POST['deleteInvoice'])) {
            $id = (int) Request::post('id', true);
            if (InvoiceModel::delete($id)) {
                Redirect::to('rental/invoices/index.php');
            } else {
                Redirect::error();
            }
        }
        if (isset($_POST['deleteInvoiceItem'])) {
            $invoiceId = (int) Request::post('invoiceid', true);
            $id = (int) Request::post('id', true);
            if (InvoiceItemModel::delete($id)) {
                Redirect::to('rental/invoices/details.php?id=' . $invoiceId);
            } else {
                Redirect::error();
            }
        }
        if (isset($_POST['addInvoiceItem'])) {
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
}
