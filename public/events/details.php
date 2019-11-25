<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Calendar\CalendarEventMapper;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('events');
$event = CalendarEventMapper::getById($_GET['id']);

$templates = new League\Plates\Engine(DIR_VIEW);
echo $templates->render('Pages/Events/details', ['event' => $event]);
