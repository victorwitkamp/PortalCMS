<?php

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Alert;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Modules\Invoices\InvoiceItemMapper;
use PortalCMS\Modules\Invoices\InvoiceMapper;

$pageName = 'Factuur';
$invoice = InvoiceMapper::getById(Request::get('id'));
if (!empty($invoice)) {
    $pageName = 'Factuur: ' . $invoice->factuurnummer;
    $contract = ContractMapper::getById($invoice->contract_id);
} else {
    Redirect::to('Error/NotFound');
} ?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <form method="post">
            <a href="Index" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
            <?php $msg = 'Weet u zeker dat u factuur met nummer ' . $invoice->factuurnummer . ' wilt verwijderen?'; ?>
            <input type="hidden" name="id" value="<?= $invoice->id ?>">
            <button type="submit" name="deleteInvoice" class="btn btn-danger btn-sm" title="Verwijderen" onclick="return confirm('<?= $msg ?>')">
                <span class="fa fa-trash"></span>
            </button>
        </form>
        <hr>
        <?php Alert::renderFeedbackMessages(); ?>
        <h3>Details</h3>
        <table class="table table-striped table-condensed">
            <tr>
                <th>Factuurnummer</th>
                <td><?= $invoice->factuurnummer ?></td>
            </tr>
            <tr>
                <th>Huurder</th>
                <td><?= $contract->band_naam ?></td>
            </tr>
            <tr>
                <th>CreationDate</th>
                <td><?= $invoice->CreationDate ?></td>
            </tr>
            <tr>
                <th>Factuurdatum</th>
                <td><?= $invoice->factuurdatum ?></td>
            </tr>
            <tr>
                <th>Vervaldatum</th>
                <td><?= $invoice->vervaldatum ?></td>
            </tr>
        </table>
        <h3>Items</h3>
        <table class="table table-striped table-condensed">
            <tr>
                <th>Acties</th>
                <th>Omschrijving</th>
                <th>Prijs</th>
            </tr>
            <?php
            $invoiceitems = InvoiceItemMapper::getByInvoiceId($invoice->id);
            foreach ($invoiceitems as $invoiceitem) { ?>
                <tr>
                    <td>
                        <?php if ($invoice->status === '0') { ?>
                            <form method="post">
                                <input type="hidden" name="invoiceid" value="<?= $invoice->id ?>">
                                <input type="hidden" name="id" value="<?= $invoiceitem->id ?>">
                                <button type="submit" name="deleteInvoiceItem" onclick="return confirm('Weet je zeker dat je <?= $invoiceitem->name ?> wilt verwijderen?')" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
                            </form>
                        <?php } ?>
                    </td>
                    <td><?= $invoiceitem->name ?></td>
                    <td><?= '&euro; ' . $invoiceitem->price ?></td>
                </tr>
            <?php } ?>
        </table>
        <h3>Items toevoegen</h3>
        <?php if ($invoice->status === '0') { ?>
            <form method="post">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Omschrijving</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Prijs</label>
                        <input type="text" name="price" class="form-control">
                    </div>
                </div>
                <input type="hidden" name="invoiceid" value="<?= $invoice->id ?>">
                <input type="submit" name="addInvoiceItem" class="btn btn-primary">
            </form>
        <?php } else { ?>
            <p>Je kunt de factuur niet meer bewerken</p>
        <?php } ?>
    </div>

<?= $this->end();
