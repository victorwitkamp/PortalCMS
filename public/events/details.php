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
?>
<div class="row">
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_TITLE') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $event->title ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_ADDED_BY') ?>:</strong></div>
    <div class="col-sm-6"><p><?php $User = UserPDOReader::getProfileById($event->CreatedBy); ?>
    <a href="/profiles/profile.php?id=<?= $User->user_id ?>"><?= $User->user_name ?></a></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_START') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $event->start_event ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_END') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $event->end_event ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_DESC') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $event->description ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_STATUS') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $event->status ?></p></div>
</div>
