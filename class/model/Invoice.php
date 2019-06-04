<?php

class Invoice
{
    public static function create()
    {
        $contract_id = Request::post('contract_id', true);
        $year = Request::post('year', true);
        $month = Request::post('month', true);
        $contract = Contract::getById($contract_id);
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

    public static function displayInvoiceSumById($id) {
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

}
