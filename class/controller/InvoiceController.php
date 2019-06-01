<?php

/**
 * InvoiceController
 * Controls everything that is invoice-related
 */
class InvoiceController extends controller
{
    public function __construct() {
        if (isset($_POST['saveNewInvoice'])) {
            Invoice::new();
        }
        if (isset($_POST['deleteinvoiceitem'])) {
            Invoice::deleteInvoiceItem();
        }
        if (isset($_POST['addinvoiceitem'])) {
            Invoice::addInvoiceItem();
        }
    }
}