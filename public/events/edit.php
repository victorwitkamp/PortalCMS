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
require_once DIR_INCLUDES . 'functions.php';

if (!empty($event = CalendarEventMapper::getById($_GET['id']))) {
    $allowEdit = true;
    $pageName = 'Evenement ' . $event->title . ' bewerken';
} else {
    Session::add('feedback_negative', 'Geen resultaten voor opgegeven event ID.');
    Redirect::to('includes/error.php');
}

require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_CSS_tempusdominus();
PortalCMS_JS_headJS();
PortalCMS_JS_tempusdominus();
PortalCMS_JS_Datepicker_event();
PortalCMS_JS_JQuery_Simple_validator(); ?>
</head>
<body>
<?php require DIR_INCLUDES . 'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h3><?= $pageName ?></h3>
            </div>
        </div>

        <div class="container">
            <?php Alert::renderFeedbackMessages(); ?>
            <form method="post" validate=true>
                <div class="form-group form-group-sm row">
                    <div class="col-sm-12">
                        <label class="control-label"><?= Text::get('LABEL_EVENT_TITLE') ?></label>
                        <input type="text" name="title" value="<?= $event->title ?>" class="form-control input-sm" placeholder="" required>
                    </div>
                </div>
                <div class="form-group form-group-sm row">
                    <div class="col-sm-6">
                        <label class="control-label"><?= Text::get('LABEL_EVENT_START') ?></label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                            <input type="text" name="start_event" value="<?= $event->start_event ?>" class="form-control input-sm datetimepicker-input" data-target="#datetimepicker1" required>
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label"><?= Text::get('LABEL_EVENT_END') ?></label>
                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                            <input type="text" name="end_event" value="<?= $event->end_event ?>" class="form-control input-sm  datetimepicker-input" data-target="#datetimepicker2" required>
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group-sm row">
                    <div class="col-sm-12">
                        <label class="control-label"><?= Text::get('LABEL_EVENT_DESC') ?></label>
                        <input type="text" name="description" value="<?= $event->description ?>" class="form-control input-sm" required>
                    </div>
                </div>

                <hr>

                <div class="form-group form-group-sm row">
                    <div class="col-sm-6">
                        <label class="control-label"><?= Text::get('LABEL_EVENT_STATUS') ?></label>
                        <select name="status" class="form-control" required>
                            <option value="0" <?php
                            if ($event->status == 0) {
                                echo 'selected';
                            } ?>>0 - <?= Text::get('LABEL_EVENT_DRAFT') ?></option>
                            <option value="1" <?php
                            if ($event->status == 1) {
                                echo 'selected';
                            } ?>>1 - <?= Text::get('LABEL_EVENT_CONFIRMED') ?></option>
                            <option value="2" <?php
                            if ($event->status == 2) {
                                echo 'selected';
                            } ?>>2 - <?= Text::get('LABEL_EVENT_CANCELED') ?></option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="form-group form-group-sm row">
                    <input type="hidden" name="id" value="<?= $event->id ?>">
                    <button type="submit" name="updateEvent" class="btn btn-sm btn-primary"><?= Text::get('LABEL_SUBMIT') ?> <i class="far fa-save"></i></button>
                    <a href="index.php" class="btn btn-sm btn-danger"><?= Text::get('LABEL_CANCEL') ?> <i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>

    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
