<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Config\Config;

?>
<form method="post">
    <div class="d-flex flex-wrap align-items-center gap-3 mb-2 p-2 bg-body-tertiary rounded">
        <div class="form-check mb-0">
            <input type="checkbox" class="form-check-input" id="selectall"/>
            <label class="form-check-label" for="selectall">Selecteer alles</label>
        </div>
        <button type="submit" formaction="/Invoices/Write" class="btn btn-success btn-sm" title="Maak definitief">
            <i class="fas fa-check"></i> Maak definitief
        </button>
        <button type="submit" formaction="/Invoices/ScheduleMail" class="btn btn-success btn-sm" title="Batch inplannen">
            <i class="fas fa-paper-plane"></i> Batch inplannen
        </button>
    </div>
    <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
        <thead class="table-dark">
        <tr>
            <th></th>
            <th>Factuurnummer</th>
            <th>Huurder</th>
            <th>Bedrag</th>
            <th>Bekijken</th>
            <th>DateSent</th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($invoices as $invoice) { ?>
            <tr>
                <td>
                    <?php if ($invoice->status === 0) { ?>
                        <input type="checkbox" class="form-check-input row-select" id="writeInvoice<?= $invoice->id ?>"
                               name="writeInvoiceId[]" value="<?= $invoice->id ?>">
                    <?php } elseif ($invoice->status === 1) { ?>
                        <input type="checkbox" class="form-check-input row-select" id="sendcheckbox<?= $invoice->id ?>"
                               name="id[]" value="<?= $invoice->id ?>">
                    <?php } ?>
                </td>
                <td><?= $invoice->factuurnummer ?></td>
                <td>
                    <?= $invoice->contract?->band_naam ?>
                </td>
                <td>&euro; <?= $invoice->total() ?></td>
                <td>
                    <?php if ($invoice->status === 2) {
                        ?><a href="<?= Config::get('URL') ?>Email/Details?id=<?= $invoice->mail_id ?>">Mail openen</a><?php
                    } ?>
                </td>
                <td><?= ($mailDates[$invoice->id] ?? null)?->format('Y-m-d H:i:s') ?></td>
                <td>
                    <a href="/Invoices/Details?id=<?= $invoice->id ?>" title="Details" class="btn btn-primary"><span class="fas fa-edit"></span></a>
                    <a href="/Invoices/CreatePDF?id=<?= $invoice->id ?>" title="PDF maken" class="btn btn-success"><span class="fas fa-file-pdf"></span></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <script>
        document.getElementById("selectall").addEventListener("change", function () {
            document.querySelectorAll(".row-select").forEach(function (checkbox) {
                checkbox.checked = this.checked;
            }, this);
        });
    </script>
</form>
