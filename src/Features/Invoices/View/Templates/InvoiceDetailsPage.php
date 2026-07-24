<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);


$pageName = 'Factuur: ' . $invoice->factuurnummer;
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <form method="post" action="/Invoices/Delete">
            <a href="/Invoices" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
            <?php $msg = 'Weet u zeker dat u factuur met nummer ' . $invoice->factuurnummer . ' wilt verwijderen?'; ?>
            <input type="hidden" name="id" value="<?= $invoice->id ?>">
            <button type="submit" class="btn btn-danger btn-sm" title="Verwijderen"
                    onclick="return confirm('<?= $msg ?>')">
                <span class="fa fa-trash"></span>
            </button>
        </form>
        <hr>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
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
                <td><?= $invoice->CreationDate->format('Y-m-d H:i:s') ?></td>
            </tr>
            <tr>
                <th>Factuurdatum</th>
                <td><?= $invoice->factuurdatum->format('Y-m-d') ?></td>
            </tr>
            <tr>
                <th>Vervaldatum</th>
                <td><?= $invoice->vervaldatum?->format('Y-m-d') ?></td>
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
            foreach ($invoice->items() as $invoiceitem) { ?>
                <tr>
                    <td>
                        <?php if ($invoice->status === 0) { ?>
                            <form method="post" action="/Invoices/Items/Delete">
                                <input type="hidden" name="invoiceid" value="<?= $invoice->id ?>">
                                <input type="hidden" name="id" value="<?= $invoiceitem->id ?>">
                                <button type="submit"
                                        onclick="return confirm('Weet je zeker dat je <?= $invoiceitem->name ?> wilt verwijderen?')"
                                        class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
                            </form>
                        <?php } ?>
                    </td>
                    <td><?= $invoiceitem->name ?></td>
                    <td><?= '&euro; ' . $invoiceitem->price ?></td>
                </tr>
            <?php } ?>
        </table>
        <h3>Items toevoegen</h3>
        <?php if ($invoice->status === 0) { ?>
            <form method="post" action="/Invoices/Items/Add">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label>Omschrijving</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label>Prijs</label>
                        <input type="text" name="price" class="form-control">
                    </div>
                </div>
                <input type="hidden" name="invoiceid" value="<?= $invoice->id ?>">
                <button type="submit" class="btn btn-primary">Toevoegen</button>
            </form>
        <?php } else { ?>
            <p>Je kunt de factuur niet meer bewerken</p>
        <?php } ?>
    </div>

<?= $this->end();
