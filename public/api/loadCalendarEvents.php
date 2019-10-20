<?php

use PortalCMS\Authentication\Authentication;
use PortalCMS\Models\Event;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$startdate = $_GET['start'];
$enddate = $_GET['end'];
Authentication::checkAuthentication();
Event::loadCalendarEvents($startdate, $enddate);
