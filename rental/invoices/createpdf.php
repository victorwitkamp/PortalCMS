<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Auth::checkPrivilege("rental-invoices")) {
    Redirect::permissionError();
    die();
}
InvoiceController::render($_GET['id']);
