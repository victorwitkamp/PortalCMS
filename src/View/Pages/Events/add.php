<?php

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\Session\Session;

$pageName = (string) Text::get('TITLE_EVENTS_ADD');

?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
    <script src="/dist/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js" async></script>
    <script src="/includes/js/datepicker_event.js"></script>
    <!-- <script src="/includes/js/jquery-simple-validator.nl.js"></script> -->
    <!-- <link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css"> -->

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <hr>
    <div class="container">
        <?php Alert::renderFeedbackMessages(); ?>
        <form method="post" validate=true>
            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label"><?= Text::get('LABEL_EVENT_TITLE') ?></label>
                    <input type="text" name="title" class="form-control" placeholder="" required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label"><?= Text::get('LABEL_EVENT_START') ?></label>
                    <div class="form-group date" id="datetimepicker1" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" name="start_event" maxlength="16" class="form-control datetimepicker-input" data-target="#datetimepicker1" required>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="control-label"><?= Text::get('LABEL_EVENT_END') ?></label>
                    <div class="form-group date" id="datetimepicker2" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" name="end_event" maxlength="16" class="form-control datetimepicker-input" data-target="#datetimepicker2" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label"><?= Text::get('LABEL_EVENT_DESC') ?></label>
                    <input type="text" name="description" class="form-control" placeholder="">
                </div>
            </div>
            <hr />
            <div class="form-group form-group-sm">
                <input type="text" name="CreatedBy" value="<?= Session::get('user_id') ?>" hidden>
                <input type="submit" name="addEvent" class="btn btn-primary" value="<?= Text::get('LABEL_SUBMIT') ?>">
                <a href="index.php" class="btn btn-danger"><?= Text::get('LABEL_CANCEL') ?></a>
            </div>
        </form>
    </div>

<?= $this->end() ?>
