<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = (string) Text::get('TITLE_EVENTS_ADD');

?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <hr>
    <div class="container">
        <?php Alert::renderFeedbackMessages(); ?>
        <form method="post">
            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label"><?= Text::get('LABEL_EVENT_TITLE') ?>
                        <input type="text" name="title" class="form-control" placeholder="" required>
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label"><?= Text::get('LABEL_EVENT_START') ?>
                        <input type="datetime-local" name="start_event" class="form-control" required>
                    </label>
                </div>
                <div class="col-sm-6">
                    <label class="control-label"><?= Text::get('LABEL_EVENT_END') ?>
                        <input type="datetime-local" name="end_event" class="form-control" required>
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label"><?= Text::get('LABEL_EVENT_DESC') ?>
                        <input type="text" name="description" class="form-control">
                    </label>
                </div>
            </div>
            <hr />
            <div class="row">
                <input type="text" name="CreatedBy" value="<?= Session::get('user_id') ?>" hidden>
                <input type="submit" name="addEvent" class="btn btn-primary" value="<?= Text::get('LABEL_SUBMIT') ?>">
                <a href="/Events" class="btn btn-danger"><?= Text::get('LABEL_CANCEL') ?></a>
            </div>
        </form>
    </div>

<?= $this->end();
