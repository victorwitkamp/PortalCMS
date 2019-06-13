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

        if (isset($_POST['createInvoiceMail'])) {
            Invoice::createMail();
        }
        if (isset($_POST['createInvoice'])) {
            if (!Invoice::create()) {
                Redirect::error();
            } else {
                Redirect::to("rental/invoices/");
            }
        }

        if (isset($_POST['deleteInvoiceItem'])) {
            $invoiceId = Request::post('invoiceid', true);
            $id = (int) Request::post('id', true);
            if (!InvoiceItem::delete($id)) {
                Redirect::error();
            } else {
                Redirect::to("rental/invoices/details.php?id=".$invoiceId);
            }
        }

        if (isset($_POST['addinvoiceitem'])) {
            $invoiceId = Request::post('invoiceid', true);
                    $invoiceId = (int) Request::post('invoiceid', true);
        $name = Request::post('name', true);
        $price = (int) Request::post('price', true);
            if (!InvoiceItem::create($invoiceId, $name, $price)) {
                Redirect::error();
            } else {
                Redirect::to("rental/invoices/details.php?id=".$invoiceId);
            }
        }
    }

    public static function render($id = null)
    {
        if (empty($id)) {
            return false;
        }
        $invoice = InvoiceMapper::getById($id);
        $contract = ContractMapper::getById($invoice['contract_id']);
        if (InvoicePDF::render($invoice, $contract)) {
            return true;
        }
    }

}