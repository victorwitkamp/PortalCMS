<?php

use PortalCMS\Modules\Calendar\CalendarEventModel;
// use PortalCMS\Core\Authentication\Authentication;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
// Authentication::checkAuthentication();
echo CalendarEventModel::loadComingEvents();
