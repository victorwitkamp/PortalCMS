<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Modules\Calendar\CalendarEventMapper;
use PortalCMS\Core\HTTP\Request;

require __DIR__ . '/../Init.php';
Authentication::checkAuthentication();
return CalendarEventMapper::updateDate(
    Request::post('id'),
    Request::post('title'),
    Request::post('start'),
    Request::post('end')
);
