<?php

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Members\MemberModel;
$pageName = Text::get('TITLE_MEMBERS');
$year = Request::get('year');
if (!isset($year)) {
    Redirect::to('membership/?year=' . date('Y'));
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
                <div class="col-sm-8"><h1><?= $pageName ?></h1></div>
                <div class="col-sm-4">
                    <a href="/Membership/Import" class="btn btn-info float-right"><span class="fa fa-plus"></span> <?= Text::get('LABEL_IMPORT') ?></a>
                    <a href="/Membership/New" class="btn btn-success float-right"><span class="fa fa-plus"></span> <?= Text::get('LABEL_ADD') ?></a>
                </div>
            </div>
            <form method="post"><label>Jaar</label><input type="text" name="year" value="<?= $year ?>"/><input type="submit" name="showMembersByYear"/></form>
        <hr>
        <?php
        Alert::renderFeedbackMessages();
        $members = MemberModel::getMembersByYear($year);
        if (empty($members)) {
            echo Text::get('LABEL_NOT_FOUND');
        } else { ?>
            <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th>Acties</th>
                        <th>Naam</th>
                        <th>Betalingswijze</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($members as $member) { ?>
                        <tr>
                            <td>
                                <form method="post">
                                    <a href="profile.php?id=<?= $member->id ?>" title="Lidmaatschap bekijken" class="btn btn-primary btn-sm">
                                        <span class="fa fa-user"></span>
                                    </a>
                                    <a href="edit.php?id=<?= $member->id ?>" title="Gegevens wijzigen" class="btn btn-warning btn-sm">
                                        <span class="fa fa-edit"></span>
                                    </a>
                                    <input name="id" type="hidden" value="<?= $member->id ?>">
                                    <button name="deleteMember" type="submit" onclick="return confirm('Weet je zeker dat je <?= $member->voornaam ?> <?= $member->achternaam ?> wilt verwijderen?')"
                                            class="btn btn-sm btn-danger" ><i class="far fa-trash-alt"></i></button>
                                </form>
                            </td>
                            <td><?= $member->voornaam . ' ' . $member->achternaam ?></td>
                            <td><?= $member->betalingswijze ?></td>
                            <td><?php
                            if ($member->status === 0) {
                                echo '0. Nieuw';
                            }
                            if ($member->status === 1) {
                                echo '1. Incasso opdracht verzonden';
                            }
                            if ($member->status === 11) {
                                echo '1.1 Niet verstuurd: rekeningnummer onjuist';
                            }
                            if ($member->status === 2) {
                                echo '2. Betaling per incasso gelukt';
                            }
                            if ($member->status === 21) {
                                echo '2.1 Incasso mislukt: rekeningnummer onjuist';
                            }
                            if ($member->status === 3) {
                                echo '3';
                            }
                            if ($member->status === 4) {
                                echo '4';
                            }
                            ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        </div>

<?= $this->end() ?>