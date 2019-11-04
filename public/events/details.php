<?php

use PortalCMS\Core\View\Text;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Modules\Calendar\CalendarEventMapper;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('events');
$row = CalendarEventMapper::getById($_GET['id']);
?>

<div class="row">
    <!-- <div class="col-sm-6"><strong>ID:</strong></div> -->
    <!-- <div class="col-sm-6"><p><?php //echo $row->id;?></p></div> -->
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_TITLE') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $row->title ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_ADDED_BY') ?>:</strong></div>
    <div class="col-sm-6"><p><?php
    $User = UserPDOReader::getProfileById($row->CreatedBy);
    echo $User->user_name; ?></p></div>

    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_START') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $row->start_event ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_END') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $row->end_event ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_DESC') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $row->description ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_STATUS') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $row->status ?></p></div>

            <!-- <div class="form-group"> -->

        <!-- </div> -->
    <!-- <hr>
    <div class="col-sm-6"><strong>Bestuursdienst:</strong></div>
    <div class="col-sm-6"><p></p></div>
    <div class="col-sm-6"><strong>Deurdienst:</strong></div>
    <div class="col-sm-6"><p></p></div>
    <div class="col-sm-6"><strong>Bardienst:</strong></div>
    <div class="col-sm-6"><p></p></div>
    <div class="col-sm-6"><strong>Licht/geluid:</strong></div>
    <div class="col-sm-6"><p></p></div> -->
</div>
