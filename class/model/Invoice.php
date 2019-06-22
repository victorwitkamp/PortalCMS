<?php

class Invoice
{
    public static function createMail()
    {
        $sender_email = Config::get('EMAIL_SMTP_USERNAME');
        // $recipient_email = Request::post('recipient_email', true);
        // $subject = Request::post('subject', true);
        // $body = Request::post('body', true);
        $invoiceId = Request::post('id', true);
        $subject = 'factuur';
        $body = 'Hey een factuur';

        $invoice = InvoiceMapper::getById($invoiceId);
        $contract = ContractMapper::getById($invoice['contract_id']);
        $recipient_email = $contract['bandleider_email'];
        $attachment = "content/invoices/".$invoice['factuurnummer'].".pdf";

        $create = MailScheduleMapper::create($sender_email, $recipient_email, null, $subject, $body, $attachment);
        if (!$create) {
            Session::add('feedback_negative', "Nieuwe email aanmaken mislukt.");
            return false;
        }
        $created = MailScheduleMapper::lastInsertedId();
        InvoiceMapper::updateMailId($invoiceId,$created);
        InvoiceMapper::updateStatus($invoiceId,2);
        Session::add('feedback_positive', "Email toegevoegd (ID = ".$created.')');
        Redirect::mail();
        return true;
    }

    public static function getByContractId($contract_id)
    {
        $Invoices = InvoiceMapper::getByContractId($contract_id);
        if (!$Invoices) {
            return false;
        }
        return $Invoices;
    }

    public static function create()
    {
        $contract_id = Request::post('contract_id', true);
        $year = Request::post('year', true);
        $month = Request::post('month', true);
        $contract = ContractMapper::getById($contract_id);
        $factuurnummer = $year.$contract['bandcode'].$month;
        $factuurdatum = Request::post('factuurdatum', true);
        $vervaldatum = Request::post('vervaldatum', true);
        if (InvoiceMapper::getByFactuurnummer($factuurnummer)) {
            Session::add('feedback_negative', "Factuurnummer bestaat al.");
            return Redirect::error();
        }
        if (!InvoiceMapper::create($contract_id, $factuurnummer, $year, $month, $factuurdatum, $vervaldatum)) {
            Session::add('feedback_negative', "Toevoegen van factuur mislukt.");
            return Redirect::error();
        }
        $invoice = InvoiceMapper::getByFactuurnummer($factuurnummer);
        if ($contract['kosten_ruimte'] > 0) {
            InvoiceItemMapper::create($invoice['id'], 'Kosten voor: huur '.Text::get('MONTH_'.$month), $contract['kosten_ruimte']);
        }
        if ($contract['kosten_kast'] > 0) {
            InvoiceItemMapper::create($invoice['id'], 'Kosten voor: kast '.Text::get('MONTH_'.$month), $contract['kosten_kast']);
        }
        Session::add('feedback_positive', "Factuur toegevoegd.");
        return Redirect::to("rental/invoices/index.php");
    }

    public static function displayInvoiceSumById($id)
    {
        $sum = self::getInvoiceSumById($id);
        if (!$sum) {
            return false;
        }
        return '&euro; '.$sum;
    }

    public static function getInvoiceSumById($id)
    {
        $sum = 0;
        $invoiceitems = InvoiceItemMapper::getByInvoiceId($id);
        foreach ($invoiceitems as $row) {
            $sum = $sum + $row['price'];
        }
        return $sum;
    }

    public static function delete()
    {
        $id = (int) Request::post('id', true);
        if (!InvoiceMapper::getById($id)) {
            Session::add('feedback_negative', "Kan factuur niet verwijderen.<br>Factuur bestaat niet.");
            return Redirect::error();
        }
        if (InvoiceItemMapper::getByInvoiceId($id)) {
            if (!InvoiceItemMapper::deleteByInvoiceId($id)) {
                Session::add('feedback_negative', "Verwijderen van factuuritems voor factuur mislukt.");
                return Redirect::error();
            }
        }
        if (!InvoiceMapper::delete($id)) {
            Session::add('feedback_negative', "Verwijderen van factuur mislukt.");
            return Redirect::error();
        }
        Session::add('feedback_positive', "Factuur verwijderd.");
        return Redirect::to("rental/invoices/index.php");
    }

    public static function render($id = null)
    {
        if (empty($id)) {
            return false;
        }
        $invoice = InvoiceMapper::getById($id);
        $invoiceitems = InvoiceItemMapper::getByInvoiceId($id);
        $contract = ContractMapper::getById($invoice['contract_id']);
        if (PDF::renderInvoice($invoice, $invoiceitems, $contract)) {
            return true;
        }
    }

    public static function write()
    {
        $id = Request::post('id', true);

        if (empty($id)) {
            return false;
        }
        $invoice = InvoiceMapper::getById($id);
        $contract = ContractMapper::getById($invoice['contract_id']);
        $invoiceitems = InvoiceItemMapper::getByInvoiceId($id);
        if (!PDF::writeInvoice($invoice, $invoiceitems, $contract)) {
            return Redirect::error();
        }
        InvoiceMapper::updateStatus($id, 1);
        // return Redirect::to("content/invoices/".$invoice['factuurnummer'].".pdf");
        return true;
    }
}
