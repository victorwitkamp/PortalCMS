<?php

namespace PortalCMS\Modules\Invoices;

use PortalCMS\Modules\Invoices\InvoiceItemMapper;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;

class InvoiceItem
{
    public static function create()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $name = Request::post('name', true);
        $price = (int) Request::post('price', true);
        if (!InvoiceItemMapper::create($invoiceId, $name, $price)) {
            Session::add('feedback_negative', "Toevoegen van factuuritem mislukt.");
            return Redirect::error();
        }
        Session::add('feedback_positive', "Factuuritem toegevoegd.");
        return Redirect::to("rental/invoices/details.php?id=".$invoiceId);
    }

    public static function delete()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $id = (int) Request::post('id', true);
        if (!InvoiceItemMapper::exists($id)) {
            Session::add('feedback_negative', "Kan factuuritem niet verwijderen.<br>Factuuritem bestaat niet.");
            return Redirect::error();
        }
        if (!InvoiceItemMapper::delete($id)) {
            Session::add('feedback_negative', "Verwijderen van factuuritem mislukt.");
            return Redirect::error();
        }
        Session::add('feedback_positive', "Factuuritem verwijderd.");
        return Redirect::to("rental/invoices/details.php?id=".$invoiceId);
    }
}
