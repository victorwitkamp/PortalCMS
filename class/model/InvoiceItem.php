<?php

class InvoiceItem
{
    public static function create()
    {
        $invoiceId = (int)Request::post('invoiceid', true);
        $name = Request::post('name', true);
        $price = (int)Request::post('price', true);
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
        $id = (int)Request::post('id', true);
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