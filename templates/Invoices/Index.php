<?php



declare(strict_types=1);

use App\Core\HTTP\Request;
use App\Core\Controller\AlertController;
use App\Modules\Invoices\InvoiceMapper;

?>
<?php echo $this->layout('layout', ['title' => $title]); ?>
<?php echo $this->push('head-extra'); ?>

    <link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">
    <script src="/dist/merged/dataTables.min.js"></script>
    <script src="/includes/js/init.datatables.js" class="init"></script>

<?php echo $this->end(); ?>
<?php echo $this->push('main-content'); ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?php echo $title; ?></h1>
            </div>
            <div class="col-sm-4"><a href="/Invoices/Add" class="btn btn-success navbar-btn float-right"><span
                            class="fa fa-plus"></span> Toevoegen</a></div>
        </div>

        <ul>
            <li><a href="/Invoices">Alle</a> (<?php echo InvoiceMapper::getInvoiceCount(); ?>)</li>
            <?php
            $years = InvoiceMapper::getYears();
            foreach ($years as $jaar) {
                ?>
                <li><a href="/Invoices?year=<?php echo $jaar['year']; ?>"><?php echo $jaar['year']; ?></a>
                (<?php echo InvoiceMapper::getInvoiceCountByYear($jaar['year']); ?>) <?php if ((int) $this->request->get('year') === $jaar['year']) {
                    echo ' - Geselecteerd';
                } ?></li><?php
            } ?>
        </ul>


        <hr>
        <?php $Alert = new AlertController();$Alert->renderFeedbackMessages(); ?>
    </div>

<?php if (!empty($invoices)) { ?>
    <div class="container-fluid">
        <?php include_once DIR_VIEW.'Pages/Invoices/Inc/Table.php'; ?>
    </div>
<?php } else { ?>
    <div class="container">
        <?php echo 'Geen facturen gevonden.'; ?>
    </div>
<?php } ?>

<?php echo $this->end();
