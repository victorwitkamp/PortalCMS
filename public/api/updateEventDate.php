<?php

use PortalCMS\Authentication\Authentication;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Authentication::checkAuthentication();
if (EventMapper::updateDate($_POST['id'], $_POST['title'], $_POST['start'], $_POST['end'])) {
    return true;
}
