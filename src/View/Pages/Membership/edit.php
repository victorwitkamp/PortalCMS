<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Modules\Members\MemberModel;

$pageName = 'Wijzigen';
$pageType = 'edit';
$member = MemberModel::getMemberById((int) Request::get('Id'));
if (!empty($member)) {
    $allowEdit = true;
    $pageName = 'Lidmaatschap van ' . $member->voornaam . ' ' . $member->achternaam . ' bewerken';
} else {
    Redirect::to('Error/NotFound');
}
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
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
        <?php require __DIR__ . '\inc\form.php'; ?>
    </div>

<?= $this->end();
