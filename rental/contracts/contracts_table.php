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

        $result = $stmt->fetchAll();
        foreach ($result as $row) {
    echo '
        <tr>
            <td>
                <a href="contract.php?id='.$row['id'].'" title="Contract bekijken" class="btn btn-primary btn-sm">
                    <span class="fa fa-user"></span>
                </a>
                <a href="edit.php?id='.$row['id'].'" title="Gegevens wijzigen" class="btn btn-warning btn-sm">
                    <span class="fa fa-edit"></span>
                </a>
                <form method="post">
                    <input type="hidden" name="id" value="'.$row['id'].'">
                    <button type="submit" name="deleteContract" class="btn btn-danger btn-sm" onclick="return confirm(\'Weet u zeker dat u het contract van '.$row['band_naam'].' wilt verwijderen?\')"><span class="fa fa-trash"></span></button>
                </form>
            </td>
            <td>'.$row['bandcode'].'</td>
            <td>'.$row['band_naam'].'</td>
        </tr>
        ';
        $row['id']++;
    }
?>
    </tbody>

</table>