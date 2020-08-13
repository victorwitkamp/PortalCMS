<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

?>
<?= $this->layout('layout', [ 'title' => $this->e($pageName) ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h3><?= $this->e($pageName) ?></h3>
        </div>
    </div>

    <div class="container">
        <?php Alert::renderFeedbackMessages(); ?>
        <form method="post">
            <input name="id" type="hidden" value="<?= $event->id ?>">
            <button name="deleteEvent" type="submit" class="btn btn-outline-danger">
                <i class="far fa-trash-alt"></i> <?= Text::get('LABEL_DELETE') ?>
            </button>
        </form>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="eventTitle" class="control-label"><?= Text::get('LABEL_EVENT_TITLE') ?></label>
                    <input type="text" id="eventTitle" name="title" value="<?= $event->title ?>" class="form-control"
                           required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="eventStart" class="control-label"><?= Text::get('LABEL_EVENT_START') ?></label>
                    <input type="datetime-local" id="eventStart" name="start_event"
                           value="<?= date('Y-m-d\TH:i', strtotime($event->start_event)) ?>" class="form-control"
                           required>
                </div>
                <div class="form-group col-md-6">
                    <label for="eventEnd" class="control-label"><?= Text::get('LABEL_EVENT_END') ?></label>
                    <input type="datetime-local" id="eventEnd" name="end_event"
                           value="<?= date('Y-m-d\TH:i', strtotime($event->end_event)) ?>" class="form-control"
                           required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="eventDesc" class="control-label"><?= Text::get('LABEL_EVENT_DESC') ?></label>
                    <input type="text" id="eventDesc" name="description" value="<?= $event->description ?>"
                           class="form-control">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="eventStatus" class="control-label"><?= Text::get('LABEL_EVENT_STATUS') ?></label>
                    <select id="eventStatus" name="status" class="form-control" required>
                        <option value="0" <?= ($event->status === 0) ? 'selected' : '' ?>>0
                            - <?= Text::get('LABEL_EVENT_DRAFT') ?></option>
                        <option value="1" <?= ($event->status === 1) ? 'selected' : '' ?>>1
                            - <?= Text::get('LABEL_EVENT_CONFIRMED') ?></option>
                        <option value="2" <?= ($event->status === 2) ? 'selected' : '' ?>>2
                            - <?= Text::get('LABEL_EVENT_CANCELED') ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <input type="hidden" name="id" value="<?= $event->id ?>">
                <button type="submit" name="updateEvent" class="btn btn-outline-success"><?= Text::get('LABEL_SUBMIT') ?> <i
                            class="far fa-save"></i></button>
                <a href="/Events" class="btn btn-outline-danger"><?= Text::get('LABEL_CANCEL') ?> <i
                            class="fas fa-times"></i></a>
            </div>
        </form>
    </div>

<?= $this->end();
