<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Alert;
use PortalCMS\Modules\Calendar\CalendarEventMapper;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('events');

$event = CalendarEventMapper::getById($_GET['id']);

if (!empty($event)) {
    $allowEdit = true;
    $pageName = 'Evenement ' . $event->title . ' bewerken';
    $templates = new League\Plates\Engine(DIR_VIEW);
    echo $templates->render('Pages/Events/edit', ['event' => $event, 'pageName' => $pageName]);
} else {
    Session::add('feedback_negative', 'Geen resultaten voor opgegeven event ID.');
    Redirect::to('includes/error.php');
}
