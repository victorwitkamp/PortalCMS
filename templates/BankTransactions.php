<?php

declare(strict_types=1);

use App\Core\Controller\AlertController;
use App\Core\View\Text;

$pageName = $title;
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
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
        <div class="col-sm-4">
            <a href="/Bank/Import" class="btn btn-success float-right"><span
                    class="fa fa-plus"></span> <?= Text::get('LABEL_ADD') ?></a>
        </div>
        <hr>
        <?php $Alert = new AlertController();$Alert->renderFeedbackMessages(); ?>
    </div>
    <!--    <div class="row mt-5">-->
<!--        <div class="input-group mb-3">-->
<!--            <label class="input-group-text" for="inputGroupSelect01">Rekeningnummer</label>-->
<!--            <select class="form-select" id="inputGroupSelect01">-->
<!--                <option selected>Choose...</option>-->
<!--            </select>-->
<!--        </div>-->
<!--    </div>-->
</div>
<div class="container-fluid">
    <form method="post">
        <select name="categoryId" id="categoryId">
            <?php foreach ($categories as $category) {
                ?><option value="<?= $category->id ?>"><?= $category->code . ': ' . $category->name ?></option><?php
            }?>
        </select>
        <input type="submit" name="setCategory" value="setCategory" />
        <p>Transactions without category:</p>
        <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
            <thead class="thead-dark">
            <tr>

                <th></th>
                <th>id</th>
                <th>IBAN/BBAN</th>
                <th>Datum</th>
                <th>Bedrag</th>
                <th>Tegenrekening IBAN/BBAN</th>
                <th>Naam tegenpartij</th>
                <th>Category</th>
            </tr>
            </thead><tbody>
            <?php
            foreach ($imports as $import) {
                ?><tr>
                <td class="text-center"><input type="checkbox" name="transactionIDs[]" id="checkbox<?= $import->id ?>"
                                               value="<?= $import->id ?>"/></td>
                <td><?php echo $import->id ?></td>
                <td><?php echo $import->{'IBAN/BBAN'}  ?></td>
                <td><?php echo $import->Datum ?></td>
                <td><?php echo $import->Bedrag ?></td>
                <td><?php echo $import->{'Tegenrekening IBAN/BBAN'}  ?></td>
                <td><?php echo $import->{'Naam tegenpartij'}  ?></td>
                <td><?php echo $import->transaction_category ?></td>
                </tr><?php
            } ?>
            </tbody>
        </table>

    </form>
</div>

<?= $this->end();
