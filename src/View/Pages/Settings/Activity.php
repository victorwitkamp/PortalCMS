<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Activity\Activity;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_RECENT_ACTIVITY'); ?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">
    <script src="/dist/merged/dataTables.min.js"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-12">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <hr>
        <?php Alert::renderFeedbackMessages(); ?>
    </div>
    <div class="container">
        <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
            <thead>
            <tr>
                <th>CreationDate</th>
                <th>User ID</th>
                <th>IP Address</th>
                <th>Activity</th>
                <th>Details</th>
            </tr>
            </thead>
            <?php
            $activities = Activity::load();
            foreach ($activities as $Activity) {
                ?>
                <tr>
                    <td><?= $Activity['CreationDate'] ?></td>
                    <td><?= $Activity['user_id'] ?></td>
                    <td><?= $Activity['ip_address'] ?></td>
                    <td><?= $Activity['activity'] ?></td>
                    <td><?= $Activity['details'] ?></td>
                </tr>
                <?php
            } ?>
        </table>
        <script>
            $(document).ready(function () {
                $('#example').DataTable({
                    "columnDefs": [ {
                        "targets": 'nosort',
                        "orderable": false
                    } ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Dutch.json'
                    },
                    ordering: true,
                    order: [[1, 'asc']]
                })
            })
        </script>
    </div>

<?= $this->end();
