<?php

/**
 * InvoiceController
 * Controls everything that is invoice-related
 */
class InvoiceController extends controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['createInvoice'])) {
            if (!Invoice::create()) {
                Redirect::error();
            } else {
                Redirect::to("rental/invoices/");
            }
        }

        if (isset($_POST['deleteInvoiceItem'])) {
            $invoiceId = Request::post('invoiceid', true);
            if(!InvoiceItem::delete()) {
                Redirect::error();
            } else {
                Redirect::to("rental/invoices/details.php?id=".$invoiceId);
            }
        }

        if (isset($_POST['addinvoiceitem'])) {
            $invoiceId = Request::post('invoiceid', true);
            if(!InvoiceItem::create()) {
                Redirect::error();
            } else {
                Redirect::to("rental/invoices/details.php?id=".$invoiceId);
            }
        }
    }

    public static function render($id = NULL)
    {
        if (empty($id)) {
            return false;
        }
        $invoice = InvoiceMapper::getById($id);
        $contract = Contract::getById($invoice['contract_id']);
        if (InvoicePDF::render($invoice, $contract)) {
            return true;
        }
    }

}