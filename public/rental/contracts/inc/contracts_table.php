<?php

?>
<table id="example" class="table table-sm table-striped table-hover" style="width:100%">
    <thead class="thead-dark">
        <tr>
            <th>Huurder</th>
            <th>Bandcode</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($contracts as $contract) { ?>
            <tr>
                <td><a href="view.php?id=<?= $contract['id'] ?>"><?= $contract['band_naam'] ?></a></td>
                <td><?= $contract['bandcode'] ?></td>
            </tr>
    <?php } ?>
    </tbody>
</table>
