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
                <h3><?= $this->e($pageName) ?></h3>
            </div>
        </div>

        <div class="container">
            <?php Alert::renderFeedbackMessages(); ?>
            <form method="post">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="control-label"><?= Text::get('LABEL_EVENT_TITLE') ?>
                            <input type="text" name="title" value="<?= $event->title ?>" class="form-control" required>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label class="control-label"><?= Text::get('LABEL_EVENT_START') ?>
                            <input type="datetime-local" name="start_event" value="<?= date('Y-m-d\TH:i', strtotime($event->start_event)) ?>" class="form-control" required>
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label"><?= Text::get('LABEL_EVENT_END') ?>
                            <input type="datetime-local" name="end_event" value="<?= date('Y-m-d\TH:i', strtotime($event->end_event)) ?>" class="form-control" required>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <label class="control-label"><?= Text::get('LABEL_EVENT_DESC') ?>
                            <input type="text" name="description" value="<?= $event->description ?>" class="form-control" required>
                        </label>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="control-label"><?= Text::get('LABEL_EVENT_STATUS') ?></label>
                        <select name="status" class="form-control" required>
                            <option value="0" <?= ($event->status === 0) ? 'selected' : '' ?>>0 - <?= Text::get('LABEL_EVENT_DRAFT') ?></option>
                            <option value="1" <?= ($event->status === 1) ? 'selected' : '' ?>>1 - <?= Text::get('LABEL_EVENT_CONFIRMED') ?></option>
                            <option value="2" <?= ($event->status === 2) ? 'selected' : '' ?>>2 - <?= Text::get('LABEL_EVENT_CANCELED') ?></option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <input type="hidden" name="id" value="<?= $event->id ?>">
                    <button type="submit" name="updateEvent" class="btn btn-primary"><?= Text::get('LABEL_SUBMIT') ?> <i class="far fa-save"></i></button>
                    <a href="/Events" class="btn btn-danger"><?= Text::get('LABEL_CANCEL') ?> <i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>

<?= $this->end();
