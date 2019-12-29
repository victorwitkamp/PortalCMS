<?php

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Members\MemberModel;

$pageName = Text::get('TITLE_MEMBERS');
$year = Request::get('year');
if (!isset($year)) {
    Redirect::to('Membership?year=' . date('Y'));
}

?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<link rel="stylesheet" type="text/css" href="/dist/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<script src="/dist/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/dist/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/includes/js/init.datatables.js" class="init"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

<div class="container">
    <div class="row mt-5">
        <div class="col-sm-8">
            <h1><?= $pageName ?></h1>
        </div>
        <div class="col-sm-4"><a href="/Membership/New" class="btn btn-success float-right"><span class="fa fa-plus"></span> <?= Text::get('LABEL_ADD') ?></a></div>
    </div>
    <hr>
    <?php Alert::renderFeedbackMessages(); ?>
    <form method="post">
        <label><?= Text::get('YEAR') ?></label>
        <input type="text" name="year" value="<?= $year ?>" />
        <input type="submit" name="showMembersByYear" />
    </form>
    <?php
    $members = MemberModel::getMembersByYear($year);
    if (!empty($members)) {
        include_once DIR_VIEW . 'Pages/Membership/inc/table.php';
    } else {
        echo Text::get('LABEL_NOT_FOUND');
    }
    ?>
</div>

<?= $this->end();
