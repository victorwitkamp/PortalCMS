<?php

?>
<table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
    <thead class="thead-dark">
        <tr>
            <th>Bewerken</th>
            <th>Bandcode</th>
            <th>Huurder</th>
            <th>Factuurnummer</th>
            <th>Bedrag</th>
            <th>Status</th>
            <th>PDF</th>
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

            <td>
                <?php 
                $contract = Contract::getById($row['contract_id']);

                if (isset($contract['bandcode']) AND !empty($contract['bandcode'])) {
                    echo $contract['bandcode'];
                } else {
                    echo 'leeg';
                }
                ?>
            </td>
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
            <td><?php echo $row['factuurnummer'] ?></td>
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
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>