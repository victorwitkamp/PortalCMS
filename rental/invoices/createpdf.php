<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
Invoice::renderInvoiceById($_GET['id']);


