<?php

class InvoiceItem
{
    public static function create($invoiceId, $name, $price)
    {
        if (InvoiceItemMapper::itemExists($invoiceId, $name)) {
            Session::add('feedback_negative', "Er bestaat al een factuuritem met deze opgegeven naam.");
            return false;
        }
        if (!InvoiceItemMapper::create($invoiceId, $name, $price)) {
            Session::add('feedback_negative', "Toevoegen van factuuritem mislukt.");
            return false;
        }
        Session::add('feedback_positive', "Factuuritem toegevoegd.");
        return true;
    }

    public static function delete($id)
    {
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