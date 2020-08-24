<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_PROFILE') . ' ' . $this->e($user_name);

?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>
    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <table class="table table-striped table-condensed">
            <tr>
                <th>ID</th>
                <td><?= $this->e($user_id) ?></td>
            </tr>
            <tr>
                <th>user_emails</th>
                <td><?= $this->e($user_email) ?></td>
            </tr>
            <tr>
                <th>user_active</th>
                <td><?= $this->e($user_active) ?></td>
            </tr>
            <tr>
                <th>user_account_type</th>
                <td><?= $this->e($user_account_type) ?></td>
            </tr>
            <tr>
                <th>user_last_login_timestamp</th>
                <td><?= $this->e($user_last_login_timestamp) ?></td>
            </tr>
        </table>
    </div>
<?= $this->end();
