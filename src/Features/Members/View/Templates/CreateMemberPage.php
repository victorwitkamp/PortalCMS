<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

$pageName = 'Lid toevoegen';
$pageType = 'new';
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <div class="container">
        <?= $this->insert('Members::Partials/MemberForm', [ 'pageType' => $pageType ]) ?>
    </div>

<?= $this->end();
