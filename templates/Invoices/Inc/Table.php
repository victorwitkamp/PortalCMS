<?php


declare(strict_types=1);

use App\Core\Config\Config;
use App\Modules\Contracts\ContractMapper;
use App\Modules\Invoices\InvoiceHelper;

?>
<form method="post">
    <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
        <thead class="thead-dark">
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>
                <input type="checkbox" id="selectall-writeinvoice"/>
                <button type="submit" name="writeInvoice" class="btn btn-success">
                    <i class="fas fa-check"></i>
                </button>
            </th>
            <th>
                <input type="checkbox" id="selectall-send"/>
                <button type="submit" name="createInvoiceMail" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </th>
            <th></th>
        </tr>
        <tr>
            <th>Acties</th>
            <th>Factuurnummer</th>
            <th>Huurder</th>
            <th>Bedrag</th>
            <th>Status</th>
            <th>Maak definitief</th>
            <th>Batch inplannen</th>
            <th>Bekijken</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($invoices as $invoice) { ?>
            <tr>
                <td>
                    <a href="/Invoices/Details?id=<?= $invoice->id ?>" title="Details" class="btn btn-primary">
                        <span class="fas fa-edit"></span>
                    </a>
                    <a href="/Invoices/CreatePDF?id=<?= $invoice->id ?>" title="PDF maken" class="btn btn-success">
                        <span class="fas fa-file-pdf"></span>
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
                <td><?= InvoiceHelper::displayInvoiceSumById($invoice->id) ?></td>
                <td><?php if ($invoice->status === 0) {
                    ?><i class="fas fa-lock-open"></i>Concept<?php
                    }
                    if ($invoice->status === 1) {
                        ?><i class="fas fa-lock"></i>Definitief<?php
                    }
                    ?>
                </td>
                <td>
                    <?php if ($invoice->status === 0) { ?>
                        <input type="checkbox" id="writeInvoice<?= $invoice->id ?>" name="writeInvoiceId[]"
                               value="<?= $invoice->id ?>">
                    <?php } ?>
                </td>
                <td>
                    <?php if ($invoice->status === 1 && empty($invoice->mail_id)) { ?>
                        <input type="checkbox" id="sendcheckbox<?= $invoice->id ?>" name="id[]"
                               value="<?= $invoice->id ?>">
                    <?php } ?>
                </td>
                <td>
                    <?php
                    if (empty($invoice->mail_id)) {
                        ?>n/a<?php
                    } else {
                        ?><a href="<?= Config::get('URL') ?>Email/Details?id=<?= $invoice->mail_id ?>"
                        >Mail bekijken</a><?php
                    } ?>
                </td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
    <script>
        $("#selectall-writeinvoice").on('change', function () {
            if (this.checked) {
                $("input[id^='writeInvoice']").prop('checked')
            } else {
                $("input[id^='writeInvoice']").prop('checked', false)
            }
        });
        $("#selectall-send").on('change', function () {
            if (this.checked) {
                $("input[id^='sendcheckbox']").prop('checked')
            } else {
                $("input[id^='sendcheckbox']").prop('checked', false)
            }
        });
    </script>
</form>
