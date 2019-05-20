<table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
    <thead class="thead-dark">
        <tr>
            <th>Acties</th>
            <th>Bandcode</th>
            <th>Huurder</th>
            <th>Contactpersoon</th>
            <th>Oefenruimte</th>
            <th>Dag</th>
            <th>Vanaf</th>
            <th>Tot</th>

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
                <a href="contracten.php?action=delete&id='.$row['id'].'" title="Verwijderen" onclick="return confirm(\'Weet u zeker dat u '.$row['band_naam'].' wilt verwijderen?\')"
                    class="btn btn-danger btn-sm">
                    <span class="fa fa-trash"></span>
                </a>
            </td>
            <td>'.$row['bandcode'].'</td>
            <td>'.$row['band_naam'].'</td>
            <td>'.$row['bandleider_naam'].'</td>
            <td>'.$row['huur_oefenruimte_nr'].'</td>
            <td>'.$row['huur_dag'].'</td>
            <td>'.$row['huur_start'].'</td>
            <td>'.$row['huur_einde'].'</td>
        </tr>
        ';
        $row['id']++;
    }
?>
    </tbody>

</table>