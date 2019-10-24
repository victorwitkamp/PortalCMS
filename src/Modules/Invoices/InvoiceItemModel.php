<?php

namespace PortalCMS\Modules\Invoices;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;

class InvoiceItemModel
{
    public static function create()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $name = Request::post('name', true);
        $price = (int) Request::post('price', true);
        if (!InvoiceItemMapper::create($invoiceId, $name, $price)) {
            Session::add('feedback_negative', 'Toevoegen van factuuritem mislukt.');
            Redirect::error();
            return false;
        }
        Session::add('feedback_positive', 'Factuuritem toegevoegd.');
        Redirect::to('rental/invoices/details.php?id=' . $invoiceId);
        return true;
    }

    public static function delete()
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        $id = (int) Request::post('id', true);
        if (!InvoiceItemMapper::exists($id)) {
            Session::add('feedback_negative', 'Kan factuuritem niet verwijderen.<br>Factuuritem bestaat niet.');
            Redirect::error();
            return false;
        }
        if (!InvoiceItemMapper::delete($id)) {
            Session::add('feedback_negative', 'Verwijderen van factuuritem mislukt.');
            Redirect::error();
            return false;
        }
        Session::add('feedback_positive', 'Factuuritem verwijderd.');
        Redirect::to('rental/invoices/details.php?id=' . $invoiceId);
        return true;
    }
}
