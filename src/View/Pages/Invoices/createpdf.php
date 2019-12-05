<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Modules\Invoices\InvoiceModel;


Authentication::checkAuthentication();
Authorization::verifyPermission('rental-invoices');
InvoiceModel::render($_GET['id']);
