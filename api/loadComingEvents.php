<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
// Auth::checkAuthentication();
$events = Event::loadStaticComingEvents();
echo $events;
?>