<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Config\Config;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Modules\Invoices\InvoiceHelper;

?>
<form method="post">
    <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
        <thead class="thead-dark">
        <tr>
            <th class="nosort">Acties</th>
            <th>Factuurnummer</th>
            <th>Huurder</th>
            <th>Bedrag</th>
            <th>Status</th>
            <th class="nosort">Maak definitief<br><input type="checkbox" id="selectall-writeinvoice"/>
                <button type="submit" name="writeInvoice" class="btn btn-outline-success"><i class="fas fa-check"></i></button>
            </th>
            <th class="nosort">Batch inplannen<br><input type="checkbox" id="selectall-send"/><button type="submit" name="createInvoiceMail" class="btn btn-outline-success"><i class="fas fa-paper-plane"></i></button>
            </th>
            <th class="nosort">Bekijken</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($invoices as $invoice) { ?>
            <tr>
                <td>
                    <a href="/Invoices/Details?id=<?= $invoice->id ?>" title="Details" class="btn btn-outline-primary">
                        <span class="fas fa-edit"></span>
                    </a>
                    <a href="/Invoices/CreatePDF?id=<?= $invoice->id ?>" title="PDF maken" class="btn btn-outline-success">
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
                <td>
                    <?php
                    if ($invoice->status === 0) {
                        ?><i class="fas fa-lock-open"></i> 0 - Concept<?php
                    }
                    if ($invoice->status === 1) {
                        ?><i class="fas fa-lock"></i> 1 - Klaar voor planning<?php
                    }
                    if ($invoice->status === 2) {
                        ?><i class="fas fa-lock"></i> 2 - Gepland<?php
                    }
                    if ($invoice->status === 3) {
                        ?><i class="fas fa-lock"></i> 3 - Verzonden<?php
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
                    <?php if ($invoice->status === 1) { ?>
                        <input type="checkbox" id="sendcheckbox<?= $invoice->id ?>" name="id[]"
                               value="<?= $invoice->id ?>">
                    <?php } ?>
                </td>
                <td>
                    <?php if ($invoice->status === 2) {
                        ?><a href="<?= Config::get('URL') ?>Email/Details?id=<?= $invoice->mail_id ?>">Mail
                            openen</a><?php
                    } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <script>
        $("#selectall-writeinvoice").on('change', function () {
            if (this.checked) {
                $("input[id^='writeInvoice']").prop('checked', true)
            } else {
                $("input[id^='writeInvoice']").prop('checked', false)
            }
        });
        $("#selectall-send").on('change', function () {
            if (this.checked) {
                $("input[id^='sendcheckbox']").prop('checked', true)
            } else {
                $("input[id^='sendcheckbox']").prop('checked', false)
            }
        });
        $(document).ready(function () {
            $('#example').DataTable({
                "columnDefs": [ {
                    "targets": 'nosort',
                    "orderable": false
                } ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Dutch.json'
                },
                ordering: true,
                order: [[1, 'asc']]
            })
        })
    </script>
</form>
