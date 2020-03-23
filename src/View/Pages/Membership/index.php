<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Members\MemberMapper;

$pageName = Text::get('TITLE_MEMBERS');
$year = (int) Request::get('year');
if (!isset($year) || empty($year)) {
    Redirect::to('Membership?year=' . date('Y'));
}

?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">
<script src="/dist/merged/dataTables.min.js"></script>
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


    <form method="post">
        <label><?= Text::get('YEAR') ?></label>
        <input type="number" name="year" value="<?= $year ?>" />
        <button type="submit" class="btn btn-primary" name="showMembersByYear"><i class="fab fa-sistrix"></i></button>
    </form>

    <?php
    $years = MemberMapper::getYears();
    foreach ($years as $jaar) {
    ?><li><a href="/Membership?year=<?= $jaar['jaarlidmaatschap'] ?>"><?= $jaar['jaarlidmaatschap'] ?></a> (<?= MemberMapper::getMemberCountByYear($jaar['jaarlidmaatschap']) ?>)</li><?php
                                                                                                                                                                                        }
                                                                                                                                                                                            ?>

    <hr>
    <?php Alert::renderFeedbackMessages(); ?>
    <?php
    $members = MemberMapper::getMembersByYear($year);
    if (!empty($members)) {
        include_once DIR_VIEW . 'Pages/Membership/inc/table.php';
    } else {
        echo Text::get('LABEL_NOT_FOUND');
    }
    ?>
</div>

<?= $this->end();
