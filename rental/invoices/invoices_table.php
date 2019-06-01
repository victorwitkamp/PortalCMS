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
    foreach ($invoices as $row) {
        ?>
        <tr>
            <td>
                <a href="details.php?id=<?php echo $row['id'] ?>" title="Details" class="btn btn-primary">
                <span class="fas fa-edit"></span>
                </a>
            </td>

            <td><?php echo $row['factuurnummer'] ?></td>
            <td>
                <?php
                if (isset($row['contract_id']) AND !empty($row['contract_id'])) {
                    if ($contract = Contract::getById($row['contract_id'])) {
                        echo $contract['band_naam'];
                    } else {
                        echo 'n/a';
                    }
                } else {
                    echo 'leeg';
                }
                ?>
            </td>

            <td><?php echo Invoice::DisplayInvoiceSumById($row['id']); ?></td>
            <td>
            <?php
            echo $row['status'];
            if ($row['status'] === '0') {
                echo ' <i class="fas fa-lock-open"></i>';
            } else {
                echo ' <i class="fas fa-lock"></i>'; }
            ?>
            </td>
            <td>
                <a href="createpdf.php?id=<?php echo $row['id'] ?>" title="PDF maken" class="btn btn-success">
                    <span class="fas fa-file-pdf"></span>
                </a>
            </td>
            <td>
                <form method="post"><input type="hidden" name="ïd" value="$row['id']"><button type="submit" name="confirmPayment" class="btn btn-success"><i class="fas fa-check"></i></form>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>