<?php

use PortalCMS\Modules\Calendar\CalendarEventModel;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\HTTP\Request;

require __DIR__ . '/../Init.php';
Authentication::checkAuthentication();
echo json_encode(CalendarEventModel::getByDate(Request::get('start'), Request::get('end')));
