<?php



declare(strict_types=1);

use App\Core\HTTP\Request;
use App\Core\View\Text;
use App\Modules\Bank\TransactionContactMapper;

$pageName = $title;
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

<!--    <link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">-->
<!--    <script src="/dist/merged/dataTables.min.js"></script>-->
<!--    <script src="/includes/js/init.datatables.js" class="init"></script>-->

<?= $this->end() ?>
<?= $this->push('main-content') ?>

<div class="container-fluid">
    <div class="row mt-5">
        <div class="col-sm-8">
            <h1><?= $pageName ?></h1>
        </div>
        <div class="col-sm-4">
            <a href="/Membership/New" class="btn btn-success float-right"><span
                        class="fa fa-plus"></span> <?= Text::get('LABEL_ADD') ?></a></div>
    </div>
</div>
<div class="container">

    <h2>ImportedTransactionContacts</h2>
    <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
        <tbody>
        <?php
        foreach ($currentTransactionContacts as $transactionsContact) {
            ?>
            <tr>
            <td><?= $transactionsContact->id ?></td>
            <td><?= $transactionsContact->iban ?></td>
            <td><?= $transactionsContact->name ?></td>
            </tr><?php
        } ?>
        </tbody>
    </table>

    <h2>Not imported TransactionContacts</h2>
    <form method="post" enctype="multipart/form-data">
        importid: <input type="text" name="importid" value="<?= $this->request->get('importid') ?>">
        <input type="submit" name="createalltransactioncontacts" value="createall">
    </form>

    <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
        <tbody>
        <?php
        foreach ($transactionsContacts as $transactionsContact) { ?>
            <tr>
                <td><?= $transactionsContact['id'] ?></td>
                <td><?= $transactionsContact['Naam tegenpartij'] ?></td>
                <td><?= $transactionsContact['Tegenrekening IBAN/BBAN'] ?></td>
                <td>
                    <?php
                    $contactId = TransactionContactMapper::doesExist($transactionsContact['Tegenrekening IBAN/BBAN'], $transactionsContact['Naam tegenpartij']);
                    if ($contactId === null) { ?>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="name" value="<?= $transactionsContact['Naam tegenpartij'] ?>">
                            <input type="hidden" name="iban" value="<?= $transactionsContact['Tegenrekening IBAN/BBAN'] ?>">
                            <input type="submit" name="createTransactionContact" value="create">
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>


<?= $this->end();
