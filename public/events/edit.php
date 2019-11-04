<?php

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Modules\Calendar\CalendarEventMapper;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('events');
require_once DIR_INCLUDES . 'functions.php';

if (!empty($row = CalendarEventMapper::getById($_GET['id']))) {
    $allowEdit = true;
    $pageName = 'Evenement ' . $row->title . ' bewerken';
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
                        <label class="control-label">Naam van het evenement</label>
                        <input type="text" name="title" value="<?= $row->title ?>" class="form-control input-sm" placeholder="" required>
                    </div>
                </div>
                <div class="form-group form-group-sm row">
                    <div class="col-sm-6">
                        <label class="control-label">Start</label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                            <input type="text" name="start_event" value="<?= $row->start_event ?>" class="form-control input-sm datetimepicker-input" data-target="#datetimepicker1" required>
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Einde</label>
                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                            <input type="text" name="end_event" value="<?= $row->end_event ?>" class="form-control input-sm  datetimepicker-input" data-target="#datetimepicker2" required>
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group-sm row">
                    <div class="col-sm-12">
                        <label class="control-label">Beschrijving</label>
                        <input type="text" name="description" value="<?= $row->description ?>" class="form-control input-sm" required>
                    </div>
                </div>

                <hr>

                <div class="form-group form-group-sm row">
                    <div class="col-sm-6">
                        <label class="control-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="0" <?php
                            if ($row->status == 0) {
                                echo 'selected';
                            } ?>>0 - concept</option>
                            <option value="1" <?php
                            if ($row->status == 1) {
                                echo 'selected';
                            } ?>>1 - bevestigd</option>
                            <option value="2" <?php
                            if ($row->status == 2) {
                                echo 'selected';
                            } ?>>2 - geannuleerd</option>
                        </select>
                    </div>
                </div>

                <hr>

                <div class="form-group form-group-sm row">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <button type="submit" name="updateEvent" class="btn btn-sm btn-primary">Opslaan <i class="far fa-save"></i></button>
                    <a href="index.php" class="btn btn-sm btn-danger">Annuleren <i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>

    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
