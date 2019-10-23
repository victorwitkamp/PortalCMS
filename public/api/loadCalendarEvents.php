<?php

use PortalCMS\Modules\Calendar\CalendarEventModel;
use PortalCMS\Core\Authentication\Authentication;

require $_SERVER['DOCUMENT_ROOT']. '/Init.php';
$startdate = $_GET['start'];
$enddate = $_GET['end'];
Authentication::checkAuthentication();
CalendarEventModel::loadCalendarEvents($startdate, $enddate);
