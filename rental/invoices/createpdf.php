<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Auth::checkPrivilege("rental-invoices")) {
    Redirect::permissionerror();
    die();
}
InvoiceController::render($_GET['id']);
