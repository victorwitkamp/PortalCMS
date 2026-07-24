<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = (string)Text::get('TITLE_EVENTS');

?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/bootstrap-icons/font/bootstrap-icons.min.css">
    <script src="/dist/moment/min/moment.min.js"></script>
    <script src="/dist/moment/locale/nl.js"></script>
    <script src="/dist/merged/fullcalendar.min.js"></script>
    <script src="/includes/js/calendar.js"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?= $pageName ?></h1>
            </div>
            <div class="col-sm-4">
                <a href="/Events/Add" class="btn btn-info float-end"><span
                            class="fa fa-plus"></span> <?= Text::get('LABEL_ADD') ?></a>
            </div>
        </div>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div id="calendar"></div>
                <p>
                    <span class="badge bg-info"><?= Text::get('LABEL_EVENT_DRAFT') ?></span>
                    <span class="badge bg-success"><?= Text::get('LABEL_EVENT_CONFIRMED') ?></span>
                    <span class="badge bg-danger"><?= Text::get('LABEL_EVENT_CANCELED') ?></span>
                </p>
            </div>
        </div>
    </div>

<?= $this->end() ?>
<?= $this->push('scripts') ?>

    <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalTitle" class="modal-title"><?= Text::get('LABEL_EVENT_DETAILS') ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="<?= Text::get('CLOSE') ?>"></button>
                </div>
                <div id="modalBody" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal"><?= Text::get('LABEL_CLOSE') ?></button>
                    <a class="btn btn-primary" id="eventUrl" role="button">
                        <i class="far fa-edit"></i> <?= Text::get('LABEL_EDIT') ?>
                    </a>
                    <form method="post" action="/Events/Delete">
                        <input name="id" type="hidden" id="deleteUrl">
                        <button name="deleteEvent" type="submit" class="btn btn-danger">
                            <i class="far fa-trash-alt"></i> <?= Text::get('LABEL_DELETE') ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?= $this->end();
