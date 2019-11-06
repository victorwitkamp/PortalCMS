<?php

use PortalCMS\Modules\Calendar\CalendarEventModel;
use PortalCMS\Core\Authentication\Authentication;

require __DIR__ . '/../Init.php';
Authentication::checkAuthentication();
echo json_encode(CalendarEventModel::loadComingEvents());
