<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Modules\Members\MemberModel;

$pageName = 'Wijzigen';
$pageType = 'edit';

if (MemberModel::doesMemberIdExist($_GET['id'])) {
    $row = MemberModel::getMemberById($_GET['id']);
    $allowEdit = true;
    $pageName = 'Lidmaatschap van ' . $row->voornaam . ' ' . $row->achternaam . ' bewerken';
} else {
    Redirect::to('Error/NotFound');
}
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
    <script src="/dist/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="/includes/js/jquery-simple-validator.nl.js"></script>
    <link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css">
    <script src="/includes/js/datepicker_membership.js"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <hr>
    <div class="container">
        <?php require __DIR__ . 'inc\form.php'; ?>
    </div>

<?= $this->end();
