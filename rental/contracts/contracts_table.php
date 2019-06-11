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
                <td>
                    <a href="view.php?id=<?php echo $contract['id']; ?>" title="Contract bekijken" class="btn btn-primary btn-sm">
                        <span class="fa fa-user"></span>
                    </a>
                    <a href="edit.php?id=<?php echo $contract['id']; ?>" title="Gegevens wijzigen" class="btn btn-warning btn-sm">
                        <span class="fa fa-edit"></span>
                    </a>
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $contract['id']; ?>">
                        <button type="submit" name="deleteContract"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm(\'Weet u zeker dat u het contract van <?php echo $contract['band_naam']; ?> wilt verwijderen?\')">
                        <span class="fa fa-trash"></span>
                        </button>
                    </form>
                </td>
                <td><?php echo $contract['bandcode']; ?></td>
                <td><?php echo $contract['band_naam']; ?></td>
            </tr>
            <?php
            }
    ?>
    </tbody>
</table>