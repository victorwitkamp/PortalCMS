<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Modules\Calendar\CalendarEventMapper;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Authentication::checkAuthentication();
if (CalendarEventMapper::updateDate($_POST['id'], $_POST['title'], $_POST['start'], $_POST['end'])) {
    return true;
}
