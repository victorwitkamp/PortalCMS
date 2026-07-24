<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

$pageType = 'edit';
$pageName = 'Lidmaatschap van ' . $member->voornaam . ' ' . $member->achternaam . ' bewerken';
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>


<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <hr>
    <div class="container">
        <?= $this->insert('Members::Partials/MemberForm', [
            'pageType' => $pageType,
            'member' => $member,
        ]) ?>
    </div>

<?= $this->end();
