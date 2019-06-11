<table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
    <thead class="thead-dark">
        <tr>
            <th>Acties</th>
            <th>Bandcode</th>
            <th>Huurder</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($contracts as $contract) { ?>
            <tr>
                <td><?php include 'inc/buttons.php'; ?></td>
                <td><?php echo $contract['bandcode']; ?></td>
                <td><?php echo $contract['band_naam']; ?></td>
            </tr>
    <?php } ?>
    </tbody>
</table>