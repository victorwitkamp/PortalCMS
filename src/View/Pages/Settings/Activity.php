<?php
declare(strict_types=1);

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
            <th>ID</th>
            <th>User ID</th>
            <th>Gebruikersnaam</th>
            <th>IP Address</th>
            <th>Activity</th>
            <th>Details</th>
            <th>CreationDate</th>
        </thead>
        <?php $Activities = Activity::load();
        foreach ($Activities as $Activity) {
            ?>
            <tr>
                <td><?= $Activity['id'] ?></td>
                <td><?= $Activity['user_id'] ?></td>
                <td><?= $Activity['user_name'] ?></td>
                <td><?= $Activity['ip_address'] ?></td>
                <td><?= $Activity['activity'] ?></td>
                <td><?= $Activity['details'] ?></td>
                <td><?= $Activity['CreationDate'] ?></td>
            </tr>
            <?php
        } ?>
    </table>
</div>

<?= $this->end();
