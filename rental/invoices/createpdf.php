<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Permission::hasPrivilege("rental-invoices")) {
    Redirect::permissionerror();
    die();
}
Invoice::renderInvoiceById($_GET['id']);
