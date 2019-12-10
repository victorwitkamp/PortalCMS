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
        <?php foreach ($members as $member) { ?>
            <tr>
                <td>
                    <form method="post">
                        <a href="Membership/Profile?id=<?= $member->id ?>" title="Lidmaatschap bekijken" class="btn btn-primary btn-sm">
                            <span class="fa fa-user"></span>
                        </a>
                        <a href="Membership/Edit?id=<?= $member->id ?>" title="Gegevens wijzigen" class="btn btn-warning btn-sm">
                            <span class="fa fa-edit"></span>
                        </a>
                        <input name="id" type="hidden" value="<?= $member->id ?>">
                        <button name="deleteMember" type="submit" onclick="return confirm('Weet je zeker dat je <?= $member->voornaam ?> <?= $member->achternaam ?> wilt verwijderen?')" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i></button>
                    </form>
                </td>
                <td><?= $member->voornaam . ' ' . $member->achternaam ?></td>
                <td><?= $member->betalingswijze ?></td>
                <td><?php
                        if ($member->status === 0) {
                            echo '0. Nieuw';
                        } elseif ($member->status === 1) {
                            echo '1. Incasso opdracht verzonden';
                        } elseif ($member->status === 11) {
                            echo '1.1 Niet verstuurd: rekeningnummer onjuist';
                        } elseif ($member->status === 2) {
                            echo '2. Betaling per incasso gelukt';
                        } elseif ($member->status === 21) {
                            echo '2.1 Incasso mislukt: rekeningnummer onjuist';
                        } elseif ($member->status === 3) {
                            echo '3';
                        } elseif ($member->status === 4) {
                            echo '4';
                        }
                        ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
