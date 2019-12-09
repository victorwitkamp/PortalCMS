<?php

use PortalCMS\Core\Config\Config;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Modules\Invoices\InvoiceModel;

?>
<form method="post">
<table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>
                <input type="checkbox" id="selectall-writeinvoice" />
                    <button type="submit" name="writeInvoice" class="btn btn-success">
                    <i class="fas fa-check"></i>
                </button>
            </th>
            <th>
                <input type="checkbox" id="selectall-send" />
                <button type="submit" name="createInvoiceMail" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i>
                </button>
            <th></th>
            <th></th>
        </tr>
                <tr>
            <th>Bewerken</th>
            <th>Factuurnummer</th>
            <th>Huurder</th>
            <th>Bedrag</th>
            <th>Status</th>
            <th>Voorbeeld</th>
            <th>Maak definitief</th>
            <th>Batch inplannen</th>
            <th>Bekijken</th>
            <th>Betaling</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($invoices as $invoice) { ?>
        <tr>
            <td>
                <a href="/Invoices/details?id=<?= $invoice->id ?>" title="Details" class="btn btn-primary">
                    <span class="fas fa-edit"></span>
                </a>
            </td>
            <td><?= $invoice->factuurnummer ?></td>
            <td>
                <?php
                if (!empty($invoice->contract_id)) {
                    $contract = ContractMapper::getById($invoice->contract_id);
                    if (!empty($contract)) {
                        echo $contract->band_naam;
                    }
                }
                ?>
            </td>
            <td><?= InvoiceModel::displayInvoiceSumById($invoice->id) ?></td>
            <td>
                <?php
                if ($invoice->status === 0) { ?><i class="fas fa-lock-open"></i> 0 - Concept<?php }
                if ($invoice->status === 1) { ?><i class="fas fa-lock"></i> 1 - Klaar voor planning<?php }
                if ($invoice->status === 2) { ?><i class="fas fa-lock"></i> 2 - Gepland<?php }
                if ($invoice->status === 3) { ?><i class="fas fa-lock"></i> 3 - Verzonden<?php }
                ?>
            </td>
            <td>
                <a href="/Invoices/CreatePDF?id=<?= $invoice->id ?>" title="PDF maken" class="btn btn-success">
                <span class="fas fa-file-pdf"></span>
            </a>
            </td>
            <td>
                <?php if ($invoice->status === 0) { ?>
                    <input type="checkbox" id="writeInvoice" name="writeInvoiceId[]" value="<?= $invoice->id ?>">
                <?php } ?>
            </td>
            <td>
                <?php if ($invoice->status === 1) { ?>
                    <input type="checkbox" id="sendcheckbox" name="id[]" value="<?= $invoice->id ?>">
                <?php } ?>
            </td>
            <td>
                <?php if ($invoice->status === 2) {
                    echo '<a href="';
                    echo Config::get('URL');
                    echo 'email/details?id='.$invoice->mail_id.'">Mail openen</a>';
                }  else {
                    echo 'nog geen bericht';
                } ?>
            </td>
            <td>
                <!-- <form method="post">
                    <input type="hidden" name="id" value="<?php //$invoice->id ?>">
                    <button type="submit" name="confirmPayment" class="btn btn-success" disabled><i class="fas fa-check"></i></button>
                </form> -->
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<script>
    $("#selectall-writeinvoice").on('change', function() {
        if (this.checked) {
            $("input[id='writeInvoice']").prop('checked', true)
        } else {
            $("input[id='writeInvoice']").prop('checked', false)
        }
    });
        $("#selectall-send").on('change', function() {
        if (this.checked) {
            $("input[id='sendcheckbox']").prop('checked', true)
        } else {
            $("input[id='sendcheckbox']").prop('checked', false)
        }
    });
</script>
</form>
