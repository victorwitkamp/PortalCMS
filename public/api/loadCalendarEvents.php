<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$startdate = $_GET['start'];
$enddate = $_GET['end'];
Auth::checkAuthentication();
Event::loadCalendarEvents($startdate, $enddate);
?>
