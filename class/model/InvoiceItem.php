<?php

class InvoiceItem
{
    public static function create()
    {
        $invoiceId = (int)Request::post('invoiceid', TRUE);
        $name = Request::post('name', TRUE);
        $price = (int)Request::post('price', TRUE);
        if (InvoiceItemMapper::itemExists($invoiceId, $name)) {
            Session::add('feedback_negative', "Er bestaat al een factuuritem met deze opgegeven naam.");
            return FALSE;
        }
        if (!InvoiceItemMapper::create($invoiceId, $name, $price)) {
            Session::add('feedback_negative', "Toevoegen van factuuritem mislukt.");
            return FALSE;
        }
        Session::add('feedback_positive', "Factuuritem toegevoegd.");
        return TRUE;
    }

    public static function delete()
    {
        $id = (int)Request::post('id', TRUE);
        if (!InvoiceItemMapper::exists($id)) {
            Session::add('feedback_negative', "Kan factuuritem niet verwijderen.<br>Factuuritem bestaat niet.");
            return FALSE;
        }
        if (!InvoiceItemMapper::delete($id)) {
            Session::add('feedback_negative', "Verwijderen van factuuritem mislukt.");
            return FALSE;
        }
        Session::add('feedback_positive', "Factuuritem verwijderd.");
        return TRUE;
    }

}