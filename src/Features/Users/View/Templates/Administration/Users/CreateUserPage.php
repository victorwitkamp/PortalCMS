<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

$pageName = 'Gebruiker toevoegen';
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <div class="container">
        <?= $this->insert('Users::Administration/Users/Partials/CreateUserForm') ?>
    </div>

<?= $this->end();
