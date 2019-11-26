<?php

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

?>
<?= $this->layout('layout', ['title' => $this->e($pageName)]) ?>

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
                <h3><?= $this->e($pageName) ?></h3>
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
                            <option value="0" <?php if ($event->status === 0) { echo 'selected'; } ?>>0 - <?= Text::get('LABEL_EVENT_DRAFT') ?></option>
                            <option value="1" <?php if ($event->status === 1) { echo 'selected'; } ?>>1 - <?= Text::get('LABEL_EVENT_CONFIRMED') ?></option>
                            <option value="2" <?php if ($event->status === 2) { echo 'selected'; } ?>>2 - <?= Text::get('LABEL_EVENT_CANCELED') ?></option>
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

<?= $this->end() ?>
