<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_RECENT_ACTIVITY'); ?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">
    <script src="/dist/merged/dataTables.min.js"></script>
    <script src="/includes/js/init.datatables.js" class="init"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-12">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
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
            foreach ($activities as $activity) {
                ?>
                <tr>
                    <td><?= $activity->CreationDate->format('Y-m-d H:i:s') ?></td>
                    <td><?= $activity->user_id ?></td>
                    <td><?= $this->e($activity->ip_address) ?></td>
                    <td><?= $this->e($activity->activity) ?></td>
                    <td><?= $this->e($activity->details) ?></td>
                </tr>
                <?php
            } ?>
        </table>
    </div>

<?= $this->end();
