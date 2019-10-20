<?php

use PortalCMS\Models\Event;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
// Authentication::checkAuthentication();
echo Event::loadComingEvents();
