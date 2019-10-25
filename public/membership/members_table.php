<?php

?>
<table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
    <thead class="thead-dark">
        <tr>
            <th>Acties</th>
            <th>Naam</th>
            <th>Betalingswijze</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $stmt->fetchAll();
        foreach ($result as $row) { ?>
            <tr>
                <td>
                    <a href="profile.php?id=<?php echo $row['id']; ?>" title="Lidmaatschap bekijken" class="btn btn-primary btn-sm">
                        <span class="fa fa-user"></span>
                    </a>
                    <a href="edit.php?id=<?php echo $row['id']; ?>" title="Gegevens wijzigen" class="btn btn-warning btn-sm">
                        <span class="fa fa-edit"></span>
                    </a>
                    <?php $msg = 'Weet u zeker dat u ' . $row['voornaam'] . ' ' . $row['achternaam'] . ' wilt verwijderen?'; ?>
                    <a href="index.php?action=delete&id=<?php echo $row['id']; ?>" title="Verwijderen" onclick="return confirm('<?php echo $msg; ?>')" class="btn btn-danger btn-sm">
                        <span class="fa fa-trash"></span>
                    </a>
                </td>
                <td><?php echo $row['voornaam'] . ' ' . $row['achternaam']; ?></td>
                <td><?php echo $row['betalingswijze']; ?></td>
                <td><?php
                if ($row ['status'] === '0') { echo '0. Nieuw'; }
                if ($row ['status'] === '1') { echo '1. Incasso opdracht verzonden'; }
                if ($row ['status'] === '11') { echo '1.1 Niet verstuurd: rekeningnummer onjuist'; }
                if ($row ['status'] === '2') { echo '2. Betaling per incasso gelukt'; }
                if ($row ['status'] === '21') { echo '2.1 Incasso mislukt: rekeningnummer onjuist'; }
                if ($row ['status'] === '3') { echo '3'; }
                if ($row ['status'] === '4') { echo '4'; }
                ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
