<?php



declare(strict_types=1);

use App\Core\View\Text;

$pageName = $title;
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

<link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css" xmlns="http://www.w3.org/1999/html">
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
    </div>
    <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
        <thead class="thead-dark">
            <tr>
                <th>id</th>
                <th>path</th>
                <th>processed</th>
                <th>CreationDate</th>
                <th>ModificationDate</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($imports as $import) {
                ?><tr>
                <td><a href="Transactions?importid=<?= $import->id ?>"><?= $import->id ?></a></td>
                    <td><?= $import->path ?></td>
                    <td><?= $import->processed ?></td>
                    <td><?= $import->CreationDate ?></td>
                    <td><?= $import->ModificationDate ?></td>
                </tr><?php
            } ?>
        </tbody>
    </table>
</div>

<?= $this->end();
