<?php

class Invoice
{
    public static function createMail() {
        $sender_email = Config::get('EMAIL_SMTP_USERNAME');
        $recipient_email = Request::post('recipient_email', true);
        // $subject = Request::post('subject', true);
        // $body = Request::post('body', true);
        $subject = 'factuur';
        $body = 'Hey een factuur';

        $invoiceId = Request::post('id', true);
        $invoice - InvoiceMapper::getById($invoieId);
        $contract = ContractMapper::getById($invoice['contract_id']);
        $recipient_email = $contract{['bandleider_email']};

        $create = MailScheduleMapper::create($sender_email, $recipient_email, $subject, $body);
        if (!$create) {
            Session::add('feedback_negative', "Nieuwe email aanmaken mislukt.");
            return false;
        }
        $created = MailScheduleMapper::lastInsertedId();
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
            return false;
        }
        if (!InvoiceMapper::create($contract_id, $factuurnummer, $year, $month, $factuurdatum, $vervaldatum)) {
            Session::add('feedback_negative', "Toevoegen van factuur mislukt.");
            return false;
        }
        $invoice = InvoiceMapper::getByFactuurnummer($factuurnummer);
        if ($contract['kosten_ruimte'] > 0) {
            InvoiceItem::create($invoice['id'], 'Kosten voor: huur '.Text::get('MONTH_'.$month), $contract['kosten_ruimte']);
        }
        if ($contract['kosten_kast'] > 0) {
            InvoiceItem::create($invoice['id'], 'Kosten voor: kast '.Text::get('MONTH_'.$month), $contract['kosten_kast']);
        }
        Session::add('feedback_positive', "Factuur toegevoegd.");
        return true;
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


    public static function delete($id)
    {
        if (!InvoiceMapper::getById($id)) {
            Session::add('feedback_negative', "Kan factuur niet verwijderen.<br>Factuur bestaat niet.");
            Redirect::error();
            return false;
        }
        if (InvoiceItemMapper::getByInvoiceId($id)) {
            if (!InvoiceItemMapper::deleteByInvoiceId($id)) {
                Session::add('feedback_negative', "Verwijderen van factuuritems voor factuur mislukt.");
                Redirect::error();
                return false;
            }
        }
        if (!InvoiceMapper::delete($id)) {
            Session::add('feedback_negative', "Verwijderen van factuur mislukt.");
            Redirect::error();
            return false;
        }
        Session::add('feedback_positive', "Factuur verwijderd.");
        Redirect::invoices();
        return true;
    }

}
