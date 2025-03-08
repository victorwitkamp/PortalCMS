<?php



declare(strict_types=1);

use App\Core\HTTP\Request;
use App\Core\Controller\AlertController;
use App\Core\View\Text;
use App\Modules\Bank\TransactionMapper;

$pageName = Text::get('TITLE_TRANSACTION_CATEGORIES');
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
            <div class="col-sm-8"><h1><?= $pageName ?></h1></div>
            <div class="col-sm-4">
                <a href="/Membership/New" class="btn btn-success float-right"><span class="fa fa-plus"></span> <?= Text::get('LABEL_ADD') ?></a>
            </div>
        </div>
        <h2>New category</h2>
        <form method="post">
            <input class="form-control" type="text" placeholder="Name" name="name">
            <input class="form-control" type="number" placeholder="Code" name="code">
            <input class="btn btn-sm btn-primary" type="submit" name="newCategory" id="newCategory">
        </form>
        <hr>
        <?php $Alert = new AlertController();$Alert->renderFeedbackMessages(); ?>
    </div>
    <div class="container">
        <h1>Categories</h1>
        <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
            <thead class="thead-dark">
            <tr>
                <th></th>
                <th>id</th>
                <th>name</th>
                <th>code</th>
                <th>totaal</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($categories as $category) { ?>
                <tr>
                <td>
                    <input type="checkbox" name="id[]"
                           id="checkbox<?= $category->id ?>" value="<?= $category->id ?>"/>
                </td>
                <td><?= $category->id ?></td>
                <td><?= $category->name ?></td>
                <td><?= $category->code ?></td>
                <td><?php
                if ($this->request->get('year') !== null) {
                    echo TransactionMapper::getSumByCategoryAndYear($category->id, (int) $this->request->get('year'));
                } else {
                    echo TransactionMapper::getSumByCategory($category->id);
                }
                ?></td>
                </tr><?php
            }
            ?>
            </tbody>
        </table>
        <hr>
        <h1>Categories</h1>
        <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
            <thead class="thead-dark">
            <tr>
                <th></th>
                <th>id</th>
                <th>name</th>
                <th>code</th>
                <th>totaal</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($categoriesnotused as $categorynotused) { ?>
                <tr>
                <td>
                    <input type="checkbox" name="id[]"
                           id="checkbox<?= $categorynotused->id ?>" value="<?= $categorynotused->id ?>"/>
                </td>
                <td><?= $categorynotused->id ?></td>
                <td><?= $categorynotused->name ?></td>
                <td><?= $categorynotused->code ?></td>
                <td></td>
                </tr><?php
            }
            ?>
            </tbody>
        </table>
    </div>
<?= $this->end();
