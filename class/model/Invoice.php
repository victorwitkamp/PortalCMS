<?php

class Invoice
{
    public static function create()
    {
        $contract_id = Request::post('contract_id', TRUE);
        $year = Request::post('year', TRUE);
        $month = Request::post('month', TRUE);
        $contract = Contract::getById($contract_id);
        $factuurnummer = $year.$contract['bandcode'].$month;
        $factuurdatum = Request::post('factuurdatum', TRUE);
        $vervaldatum = Request::post('vervaldatum', TRUE);
        if (InvoiceMapper::getByFactuurnummer($factuurnummer)) {
            Session::add('feedback_negative', "Factuurnummer bestaat al.");
            return FALSE;
        }
        if (!InvoiceMapper::create($contract_id, $factuurnummer, $year, $month, $factuurdatum, $vervaldatum)) {
            Session::add('feedback_negative', "Toevoegen van factuur mislukt.");
            return FALSE;
        }
        $invoice = InvoiceMapper::getByFactuurnummer($factuurnummer);
        if ($contract['kosten_ruimte'] > 0) {
            InvoiceItem::create($invoice['id'], 'Kosten voor: huur '.Text::get('MONTH_'.$month), $contract['kosten_ruimte']);
        }
        if ($contract['kosten_kast'] > 0) {
            InvoiceItem::create($invoice['id'], 'Kosten voor: kast '.Text::get('MONTH_'.$month), $contract['kosten_kast']);
        }
        Session::add('feedback_positive', "Factuur toegevoegd.");
        return TRUE;
    }

    public static function displayInvoiceSumById($id) {
        $sum = self::getInvoiceSumById($id);
        if (!$sum) {
            return FALSE;
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
