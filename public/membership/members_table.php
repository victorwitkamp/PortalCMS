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
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($result as $row) { ?>
            <tr>
                <td>
                    <form method="post">
                        <a href="profile.php?id=<?= $row->id ?>" title="Lidmaatschap bekijken" class="btn btn-primary btn-sm">
                            <span class="fa fa-user"></span>
                        </a>
                        <a href="edit.php?id=<?= $row->id ?>" title="Gegevens wijzigen" class="btn btn-warning btn-sm">
                            <span class="fa fa-edit"></span>
                        </a>
                        <input name="id" type="hidden" value="<?= $row->id ?>">
                        <button name="deleteMember" type="submit" onclick="return confirm('Weet je zeker dat je <?= $row->voornaam ?> <?= $row->achternaam ?> wilt verwijderen?')"
                                class="btn btn-sm btn-danger" ><i class="far fa-trash-alt"></i></button>
                    </form>
                </td>
                <td><?= $row->voornaam . ' ' . $row->achternaam ?></td>
                <td><?= $row->betalingswijze ?></td>
                <td><?php
                if ($row->status === 0) {
                    echo '0. Nieuw';
                }
                if ($row->status === 1) {
                    echo '1. Incasso opdracht verzonden';
                }
                if ($row->status === 11) {
                    echo '1.1 Niet verstuurd: rekeningnummer onjuist';
                }
                if ($row->status === 2) {
                    echo '2. Betaling per incasso gelukt';
                }
                if ($row->status === 21) {
                    echo '2.1 Incasso mislukt: rekeningnummer onjuist';
                }
                if ($row->status === 3) {
                    echo '3';
                }
                if ($row->status === 4) {
                    echo '4';
                }
                ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
