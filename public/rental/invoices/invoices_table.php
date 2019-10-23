<?php

use PortalCMS\Core\Config\Config;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Modules\Invoices\InvoiceModel;

?>
<table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
    <thead class="thead-dark">
        <tr>
            <th>Bewerken</th>
            <th>Factuurnummer</th>
            <th>Huurder</th>

            <th>Bedrag</th>
            <th>Status</th>
            <th>Voorbeeld</th>
            <th>Maak definitief</th>
            <th>E-mail plannen</th>
            <th>Bevestig betaling</th>
        </tr>
    </thead>
    <tbody>
    <?php



    foreach ($invoices as $invoice) {
        ?>
        <tr>
            <td>
                <a href="/rental/invoices/details.php?id=<?php echo $invoice['id'] ?>" title="Details" class="btn btn-primary">
                <span class="fas fa-edit"></span>
                </a>
            </td>

            <td><?php echo $invoice['factuurnummer'] ?></td>
            <td>
                <?php
                if (isset($invoice['contract_id']) && !empty($invoice['contract_id'])) {
                    if ($contract = ContractMapper::getById($invoice['contract_id'])) {
                        echo $contract['band_naam'];
                    } else {
                        echo 'n/a';
                    }
                } else {
                    echo 'leeg';
                } ?>
            </td>

            <td><?php echo InvoiceModel::displayInvoiceSumById($invoice['id']); ?></td>
            <td>
            <?php
            if ($invoice['status'] === '0') {
                echo '<i class="fas fa-lock-open"></i> 0 - Concept';
            }
        if ($invoice['status'] === '1') {
            echo '<i class="fas fa-lock"></i> 1 - Klaar voor planning';
        }
        if ($invoice['status'] === '2') {
            echo '<i class="fas fa-lock"></i> 2 - Gepland';
        }
        if ($invoice['status'] === '3') {
            echo '<i class="fas fa-lock"></i> 3 - Verzonden ';
        } ?>
            </td>
            <td>
                <a href="/rental/invoices/createpdf.php?id=<?php echo $invoice['id'] ?>" title="PDF maken" class="btn btn-success">
                    <span class="fas fa-file-pdf"></span>
                </a>
            </td>
            <td>
                <?php if ($invoice['status'] === '0') { ?>
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $invoice['id']; ?>">
                    <button type="submit" name="writeInvoice" class="btn btn-success"><i class="fas fa-check"></i></button>
                </form>
                <?php } ?>
            </td>
            <td>
            <?php if ($invoice['status'] === '1') { ?>
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $invoice['id']; ?>">
                    <button type="submit" name="createInvoiceMail" class="btn btn-success"><i class="fas fa-check"></i></button>
                </form>
            <?php } ?>
            <?php if ($invoice['status'] === '2') { ?>
                <form method="post">
                    <a href="<?php echo Config::get('URL').'mail/details.php?id='.$invoice['mail_id']; ?>">Mail openen</a>
                </form>
            <?php } ?>
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="ïd" value="<?php echo $invoice['id']; ?>">
                    <button type="submit" name="confirmPayment" class="btn btn-success" disabled><i class="fas fa-check"></i></button>
                </form>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>
