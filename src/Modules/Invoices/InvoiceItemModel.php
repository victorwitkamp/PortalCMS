<?php
declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

use PortalCMS\Core\Session\Session;

class InvoiceItemModel
{
    public static function create($invoiceId, $name, $price)
    {
        if (!InvoiceItemMapper::create($invoiceId, $name, $price)) {
            Session::add('feedback_negative', 'Toevoegen van factuuritem mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Factuuritem toegevoegd.');
        return true;
    }

    public static function delete(int $id)
    {
        if (!InvoiceItemMapper::exists($id)) {
            Session::add('feedback_negative', 'Kan factuuritem niet verwijderen. Factuuritem bestaat niet.');
            return false;
        }
        if (!InvoiceItemMapper::delete($id)) {
            Session::add('feedback_negative', 'Verwijderen van factuuritem mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Factuuritem verwijderd.');
        return true;
    }
}
