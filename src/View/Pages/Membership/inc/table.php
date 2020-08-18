<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

?>
<form method="post">
    <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
    <thead class="thead-dark">
    <tr>
        <th class="nosort text-center"><input type="checkbox" id="selectall"/></th>
        <th class="nosort">Acties</th>
        <th>Naam</th>
        <th>Betalingswijze</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($members as $member) { ?>
        <tr>
            <td class="text-center"><input type="checkbox" name="id[]" id="checkbox<?= $member->id ?>" value="<?= $member->id ?>"/></td>
            <td>
                <a href="/Membership/Profile?Id=<?= $member->id ?>" title="Lidmaatschap bekijken" class="btn btn-primary btn-sm"><span class="fa fa-user"></span></a>
                <a href="/Membership/Edit?Id=<?= $member->id ?>" title="Gegevens wijzigen" class="btn btn-warning btn-sm"><span class="fa fa-edit"></span></a>
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
    <div class="form-row">
        <div class="col-auto">
            <input class="btn btn-danger" type="submit" name="deleteMembersById" value="deleteMembersById"/>
        </div>
    </div>
    <div class="form-row align-items-center">
        <div class="col-auto">
            <select name="status" class="form-control">
                <option value="0" selected="">0. Nieuw</option>
                <option value="1">1. Incasso opdracht verzonden</option>
                <option value="11">1.1 Niet verstuurd: rekeningnummer onjuist</option>
                <option value="2">2. Betaling per incasso gelukt</option>
                <option value="21">2.1 Incasso mislukt: rekeningnummer onjuist</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        <div class="col-auto">
            <input class="btn btn-outline-info" type="submit" name="setPaymentStatusById" value="setPaymentStatusById"/>
        </div>
    </div>
</form>
<script>
    $("#selectall").on('change', function () {
        if (this.checked) {
            $("input[type='checkbox']").prop('checked', true)
        } else {
            $("input[type='checkbox']").prop('checked', false)
        }
    });
    $(document).ready(function () {
        $('#example').DataTable({
            "columnDefs": [ {
                "targets": 'nosort',
                "orderable": false
            } ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Dutch.json'
            },
            ordering: true,
            order: [[2, 'asc']]
        })
    })
</script>
