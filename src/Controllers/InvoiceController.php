<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controller;
use PortalCMS\Core\Redirect;
use PortalCMS\Core\Request;
use PortalCMS\Models\Invoice;
use PortalCMS\Models\InvoiceItem;

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
            Invoice::createMail();
        }
        if (isset($_POST['writeInvoice'])) {
            $id = Request::post('id', true);
            if (!Invoice::write($id)) {
                Redirect::error();
            }
        }
        if (isset($_POST['createInvoice'])) {
            Invoice::create();
        }
        if (isset($_POST['deleteInvoice'])) {
            Invoice::delete();
        }
        if (isset($_POST['deleteInvoiceItem'])) {
            InvoiceItem::delete();
        }
        if (isset($_POST['addInvoiceItem'])) {
            InvoiceItem::create();
        }
    }
}
