<?php

?>
<div class="row">
<div class="col-md-8">
<table class="table table-striped table-condensed">
    <tr>
        <th>ID</th><td><?= $row->id ?></td>
    </tr>
    <tr>
        <th>Jaar van lidmaatschap</th><td><?= $row->jaarlidmaatschap ?></td>
    </tr>
    <tr>
        <th>Voorletters</th><td><?= $row->voorletters ?></td>
    </tr>
    <tr>
        <th>Voornaam</th><td><?= $row->voornaam ?></td>
    </tr>
    <tr>
        <th>Achternaam</th><td><?= $row->achternaam ?></td>
    </tr>
    <tr>
        <th>Geboortedatum</th><td><?= $row->geboortedatum ?></td>
    </tr>
    <tr>
        <th>Adres</th><td><?= $row->adres ?></td>
    </tr>
    <tr>
        <th>Postcode</th><td><?= $row->postcode ?></td>
    </tr>
    <tr>
        <th>Huisnummer</th><td><?= $row->huisnummer ?></td>
    </tr>
    <tr>
        <th>Woonplaats</th><td><?= $row->woonplaats ?></td>
    </tr>
    <tr>
        <th>Telefoon vast</th><td><?= $row->telefoon_vast ?></td>
    </tr>
    <tr>
        <th>Telefoon mobiel</th><td><?= $row->telefoon_mobiel ?></td>
    </tr>
    <tr>
        <th>E-mailadres</th><td><?= $row->emailadres ?></td>
    </tr>
    <tr>
        <th>Lid vanaf</th><td><?= $row->ingangsdatum ?></td>
    </tr>
</table>
</div>
<div class="col-md-4">
<table class="table table-striped table-condensed">
    <tr>
        <th>Geslacht</th><td><?= $row->geslacht ?></td>
    </tr>
    <tr>
        <th>Nieuwsbrief</th>
        <td><?php
        if ($row->nieuwsbrief === '1') {
            echo 'ja';
        } elseif ($row->nieuwsbrief === '0') {
            echo 'nee';
        } else {
            echo 'n/a';
        } ?></td>
    </tr>
    <tr>
        <th>Vrijwilliger</th>
        <td><?php
        if ($row->vrijwilliger === '1') {
            echo 'ja';
        } elseif ($row->vrijwilliger === '0') {
            echo 'nee';
        } else {
            echo 'n/a';
        } ?></td>
    </tr>
    <tr>
        <th>Licht / geluid</th>
        <td><?php
        if ($row->vrijwilligeroptie1 === '1') {
            echo 'ja';
        } elseif ($row->vrijwilligeroptie1 === '0') {
            echo 'nee';
        } else {
            echo 'n/a';
        } ?></td>
    </tr>
    <tr>
        <th>Bar</th>
        <td><?php
        if ($row->vrijwilligeroptie2 === '1') {
            echo 'ja';
        } elseif ($row->vrijwilligeroptie2 === '0') {
            echo 'nee';
        } else {
            echo 'n/a';
        } ?></td>
    </tr>
    <tr>
        <th>Schoonmaken / klussen</th>
        <td><?php
        if ($row->vrijwilligeroptie3 === '1') {
            echo 'ja';
        } elseif ($row->vrijwilligeroptie3 === '0') {
            echo 'nee';
        } else {
            echo 'n/a';
        } ?></td>
    </tr>
    <tr>
        <th>Promotie / flyeren</th>
        <td><?php
        if ($row->vrijwilligeroptie4 === '1') {
            echo 'ja';
        } elseif ($row->vrijwilligeroptie4 === '0') {
            echo 'nee';
        } else {
            echo 'n/a';
        } ?></td>
    </tr>
    <tr>
        <th>Organisatie evenementen</th>
        <td><?php
        if ($row->vrijwilligeroptie5 === '1') {
            echo 'ja';
        } elseif ($row->vrijwilligeroptie5 === '0') {
            echo 'nee';
        } else {
            echo 'n/a';
        } ?></td>
    </tr>
</table>
<table class="table table-striped table-condensed">
    <tr>
        <th>CreationDate</th><td><?= $row->CreationDate ?></td>
    </tr>
    <tr>
        <th>ModificationDate</th><td><?= $row->ModificationDate ?></td>
    </tr>
</table>
</div>

<table class="table table-striped table-condensed">
    <tr>
        <th>Betalingswijze</th><td><?= $row->betalingswijze ?></td>
    </tr>
    <tr>
        <th>IBAN</th><td><?= $row->iban ?></td>
    </tr>
    <tr>
        <th>Machtigingskenmerk</th><td><?= $row->machtigingskenmerk ?></td>
    </tr>
   <!--<tr>
        <th>Incasso gelukt</th><td><?php //echo $row->incasso_gelukt; ?></td>
    </tr>-->
    <tr>
        <th>Opmerking</th><td><?= $row->opmerking ?></td>
    </tr>
</table>

</div>
