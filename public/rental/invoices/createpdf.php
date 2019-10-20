<?php

use PortalCMS\Authentication\Authentication;
use PortalCMS\Core\Redirect;
use PortalCMS\Models\Invoice;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege("rental-invoices")) {
    Redirect::permissionError();
    die();
}
Invoice::render($_GET['id']);
