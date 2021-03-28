<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

?>
<form method="post">
    <input type="text" class="form-control" name="targetYear"/>
    <input type="submit" class="btn btn-danger" name="copyMembersById" value="copyMembersById"/>
    <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
        <thead class="thead-dark">
        <tr>
            <th class="text-center"><input type="checkbox" id="selectall"/></th>
            <th>Naam</th>
            <th>Betalingswijze</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($members as $member) { ?>
            <tr>
                <td class="text-center"><input type="checkbox" name="id[]" id="checkbox<?= $member->id ?>"
                                               value="<?= $member->id ?>"/></td>
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
</form>
<script>
    $("#selectall").on('change', function () {
        if (this.checked) {
            $("input[type='checkbox']").prop('checked', true)
        } else {
            $("input[type='checkbox']").prop('checked', false)
        }
    });
</script>