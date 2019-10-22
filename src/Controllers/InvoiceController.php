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
            InvoiceModel::create();
        }
        if (isset($_POST['deleteInvoice'])) {
            InvoiceModel::delete();
        }
        if (isset($_POST['deleteInvoiceItem'])) {
            InvoiceItemModel::delete();
        }
        if (isset($_POST['addInvoiceItem'])) {
            InvoiceItemModel::create();
        }
    }
}
