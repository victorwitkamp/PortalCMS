<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\User\UserMapper;
use PortalCMS\Core\View\Text;

?>
<div class="row">
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_TITLE') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $event->title ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_ADDED_BY') ?>:</strong></div>
    <div class="col-sm-6"><p><?php $User = UserMapper::getProfileById($event->CreatedBy); ?>
    <a href="/Profile/?id=<?= $User->user_id ?>"><?= $User->user_name ?></a></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_START') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $event->start_event ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_END') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $event->end_event ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_DESC') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $event->description ?></p></div>
    <div class="col-sm-6"><strong><?= Text::get('LABEL_EVENT_STATUS') ?>:</strong></div>
    <div class="col-sm-6"><p><?= $event->status ?></p></div>
</div>
