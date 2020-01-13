<?php

use PortalCMS\Core\Activity\Activity;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_RECENT_ACTIVITY'); ?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-12">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <?php Alert::renderFeedbackMessages(); ?>
    </div>
    <div class="container">
        <table class="table table-sm table-striped table-hover table-dark">
            <thead>
            <th>CreationDate</th>
            <th>activity_id</th>
            <th>user_id</th>
            <th>user_name</th>
            <th>ip_address</th>
            <th>activity</th>
            </thead>
            <?php $Activities = Activity::load();
            foreach ($Activities as $Activity) {
                ?>
                <tr>
                    <td><?= $Activity['CreationDate'] ?></td>
                    <td><?= $Activity['id'] ?></td>
                    <td><?= $Activity['user_id'] ?></td>
                    <td><?= $Activity['user_name'] ?></td>
                    <td><?= $Activity['ip_address'] ?></td>
                    <td><?= $Activity['activity'] ?></td>
                    <td><?= $Activity['details'] ?></td>
                </tr>
                <?php
            } ?>
        </table>
    </div>

<?= $this->end();
