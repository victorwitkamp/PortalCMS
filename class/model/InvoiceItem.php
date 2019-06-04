<?php

class InvoiceItem
{
    public static function create()
    {
        $invoiceId = Request::post('invoiceid', true);
        $name = Request::post('name', true);
        $price = Request::post('price', true);
        if (InvoiceItemMapper::itemExists($invoiceId, $name, $price)) {
            Session::add('feedback_negative', "Factuuritem bestaat al");
            return false;
        }
        if (!InvoiceItemMapper::create($invoiceId, $name, $price)) {
            Session::add('feedback_negative', "Toevoegen van factuuritem mislukt.");
            return false;
        }
        Session::add('feedback_positive', "Factuuritem toegevoegd.");
        return true;
    }

    public static function delete()
    {
        $id = Request::post('id', true);
        if (!InvoiceItemMapper::exists($id)) {
            Session::add('feedback_negative', "Kan factuuritem niet verwijderen.<br>Factuuritem bestaat niet.");
            return false;
        }
        if (!InvoiceItemMapper::delete($id)) {
            Session::add('feedback_negative', "Verwijderen van factuuritem mislukt.");
            return false;
        }
        Session::add('feedback_positive', "Factuuritem verwijderd.");
        return true;
    }

}