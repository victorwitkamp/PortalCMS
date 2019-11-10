<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Modules\Invoices\InvoiceModel;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('rental-invoices');
InvoiceModel::render($_GET['id']);
