<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

?>
<?= $this->layout('layout', ['title' => $this->e($pageName)]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $this->e($pageName) ?></h1>
        </div>
    </div>
    <hr>
    <div class="container">
        <?php Alert::renderFeedbackMessages(); ?>
        <form method="post">
            <div class="form-group row">
                <div class="col-sm-12">
                    <label for="eventTitle" class="control-label"><?= Text::get('LABEL_EVENT_TITLE') ?></label>
                    <input type="text" id="eventTitle" name="title" class="form-control" placeholder="" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="eventStart" class="control-label"><?= Text::get('LABEL_EVENT_START') ?></label>
                    <input type="datetime-local" id="eventStart" name="start_event" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="eventEnd" class="control-label"><?= Text::get('LABEL_EVENT_END') ?></label>
                    <input type="datetime-local" id="eventEnd" name="end_event" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="eventDesc" class="control-label"><?= Text::get('LABEL_EVENT_DESC') ?></label>
                    <input type="text" id="eventDesc" name="description" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <input type="submit" name="addEvent" class="btn btn-outline-success" value="<?= Text::get('LABEL_SUBMIT') ?>">
                <a href="/Events" class="btn btn-outline-danger"><?= Text::get('LABEL_CANCEL') ?></a>
            </div>
        </form>
    </div>

<?= $this->end();
