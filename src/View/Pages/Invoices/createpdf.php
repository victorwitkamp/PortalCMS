<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Modules\Invoices\InvoiceHelper;

InvoiceHelper::render((int)Request::get('id'));
