<?php


declare(strict_types=1);

?>
<form method="post">
    <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
    <thead class="thead-dark">
    <tr>
        <th><input type="checkbox" id="selectall"/></th>
        <th>Acties</th>
        <th>Naam</th>
        <th>Betalingswijze</th>
        <th>Machtigingskenmerk</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($members as $member) { ?>
        <tr>
            <td class="text-center"><input type="checkbox" name="id[]" id="checkbox<?= $member->id ?>"
                                           value="<?= $member->id ?>"/></td>
            <td>

                    <a href="/Membership/Profile?Id=<?= $member->id ?>" title="Lidmaatschap bekijken"
                       class="btn btn-primary btn-sm">
                        <span class="fa fa-user"></span>
                    </a>
                    <a href="/Membership/Edit?Id=<?= $member->id ?>" title="Gegevens wijzigen"
                       class="btn btn-warning btn-sm">
                        <span class="fa fa-edit"></span>
                    </a>

            </td>
            <td><?= $member->voornaam . ' ' . $member->achternaam ?></td>
            <td><?= $member->betalingswijze ?></td>
<td><?= $member->machtigingskenmerk ?></td>
            <td><?= $member->status ?></td>
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
                <option value="0" selected="">0. Nieuw
                </option>
                <option value="1">1. Incasso
                    opdracht verzonden
                </option>
                <option value="1">1.1 Niet
                    verstuurd: rekeningnummer onjuist
                </option>
                <option value="2">2. Betaling
                    per incasso gelukt
                </option>
                <option value="2">2.1 Incasso
                    mislukt: rekeningnummer onjuist
                </option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        <div class="col-auto">

            <input class="btn btn-info" type="submit" name="setPaymentStatusById" value="setPaymentStatusById"/>
        </div>
    </div>
</form>
<script>
    $("#selectall").on('change', function () {
        if (this.checked) {
            $("input[type='checkbox']").prop('checked')
        } else {
            $("input[type='checkbox']").prop('checked', false)
        }
    });
</script>
