<?php

use PortalCMS\Modules\Invoices\InvoiceModel;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('rental-invoices');
InvoiceModel::render($_GET['id']);
