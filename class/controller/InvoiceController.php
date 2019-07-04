<?php

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
            // Invoice::write();
        }
        if (isset($_POST['writeInvoice'])) {
            Invoice::write();
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