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

        if (isset($_POST['saveNewInvoice'])) {
            if (!self::create()) {
                Redirect::error();
            } else {
                Redirect::redirectPage("rental/invoices/");
            }
        }

        if (isset($_POST['deleteinvoiceitem'])) {
            $invoiceId = Request::post('invoiceid', true);
            if(!self::deleteInvoiceItem()) {
                Redirect::error();
            } else {
                Redirect::redirectPage("rental/invoices/details.php?id=".$invoiceId);
            }
        }

        if (isset($_POST['addinvoiceitem'])) {
            $invoiceId = Request::post('invoiceid', true);
            if(!self::createInvoiceItem()) {
                Redirect::error();
            } else {
                Redirect::redirectPage("rental/invoices/details.php?id=".$invoiceId);
            }
        }
    }

    public static function render($id = null)
    {
        if (empty($id)) {
            return false;
        }
        $invoice = self::getById($id);
        $contract = Contract::getById($invoice['contract_id']);
        if (InvoicePDF::render($invoice, $contract)) {
            return true;
        }
    }

    public static function create()
    {
        $contract_id = Request::post('contract_id', true);
        $year = Request::post('year', true);
        $month = Request::post('month', true);
        $contract = Contract::getById($contract_id);
        $factuurnummer = $year.$contract['bandcode'].$month;
        $factuurdatum = Request::post('factuurdatum', true);
        $vervaldatum = Request::post('vervaldatum', true);
        if (Invoice::factuurnummerExists($factuurnummer)) {
            Session::add('feedback_negative', "Factuurnummer bestaat al.");
            return false;
        }
        if (!Invoice::create($contract_id, $factuurnummer, $year, $month, $factuurdatum, $vervaldatum)) {
            Session::add('feedback_negative', "Toevoegen van factuur mislukt.");
            return false;
        }
        $invoice = Invoice::getByFactuurnummer($factuurnummer);
        if ($contract['kosten_ruimte'] > 0) {
            InvoiceItem::create($invoice['id'], 'Kosten voor: huur '.Text::get('MONTH_'.$month), $contract['kosten_ruimte']);
        }
        if ($contract['kosten_kast'] > 0) {
            InvoiceItem::create($invoice['id'], 'Kosten voor: kast '.Text::get('MONTH_'.$month), $contract['kosten_kast']);
        }
        Session::add('feedback_positive', "Factuur toegevoegd.");
        return true;
    }

    public static function createInvoiceItem()
    {
        $invoiceId = Request::post('invoiceid', true);
        $name = Request::post('name', true);
        $price = Request::post('price', true);
        if (InvoiceItem::itemExists($invoiceId, $name, $price)) {
            Session::add('feedback_negative', "Factuuritem bestaat al");
            return false;
        }
        if (!InvoiceItem::create($invoiceId, $name, $price)) {
            Session::add('feedback_negative', "Toevoegen van factuuritem mislukt.");
            return false;
        }
        Session::add('feedback_positive', "Factuuritem toegevoegd.");
        return true;
    }

    public static function deleteInvoiceItem()
    {
        $id = Request::post('id', true);
        if (!InvoiceItem::exists($id)) {
            Session::add('feedback_negative', "Kan factuuritem niet verwijderen.<br>Factuuritem bestaat niet.");
            return false;
        }
        if (!InvoiceItem::delete($id)) {
            Session::add('feedback_negative', "Verwijderen van factuuritem mislukt.");
            return false;
        }
        Session::add('feedback_positive', "Factuuritem verwijderd.");
        return true;
    }

}