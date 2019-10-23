<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Modules\Invoices\InvoiceModel;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege('rental-invoices')) {
    Redirect::permissionError();
    die();
}
InvoiceModel::render($_GET['id']);
