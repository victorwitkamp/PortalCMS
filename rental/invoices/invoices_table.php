<?php

?>
<table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
    <thead class="thead-dark">
        <tr>
            <th>Bewerken</th>
            <th>Factuurnummer</th>
            <th>Huurder</th>

            <th>Bedrag</th>
            <th>Status</th>
            <th>PDF</th>
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
                if (isset($invoice['contract_id']) and !empty($invoice['contract_id'])) {
                    if ($contract = ContractMapper::getById($invoice['contract_id'])) {
                        echo $contract['band_naam'];
                    } else {
                        echo 'n/a';
                    }
                } else {
                    echo 'leeg';
                }
                ?>
            </td>

            <td><?php echo Invoice::DisplayInvoiceSumById($invoice['id']); ?></td>
            <td>
            <?php
            if ($invoice['status'] === '0') {
                echo '<i class="fas fa-lock-open"></i> Concept';
            }
            if ($invoice['status'] === '1') {
                echo '<i class="fas fa-lock"></i> Klaar voor verzending';
            }
            if ($invoice['status'] === '2') {
                echo '<i class="fas fa-lock"></i> Verzonden ';
            }
            ?>
            </td>
            <td>
                <a href="createpdf.php?id=<?php echo $invoice['id'] ?>" title="PDF maken" class="btn btn-success">
                    <span class="fas fa-file-pdf"></span>
                </a>
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