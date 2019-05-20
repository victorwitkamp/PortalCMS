<div class="row">
<div class="col-md-8">
<table class="table table-striped table-condensed">
    <tr>
        <th>ID</th><td><?php echo $row['id']; ?></td>
    </tr>
    <tr>
        <th>Jaar van lidmaatschap</th><td><?php echo $row['jaarlidmaatschap']; ?></td>
    </tr>
    <tr>
        <th>Voorletters</th><td><?php echo $row['voorletters']; ?></td>
    </tr>
    <tr>
        <th>Voornaam</th><td><?php echo $row['voornaam']; ?></td>
    </tr>
    <tr>
        <th>Achternaam</th><td><?php echo $row['achternaam']; ?></td>
    </tr>
    <tr>
        <th>Geboortedatum</th><td><?php echo $row['geboortedatum']; ?></td>
    </tr>
    <tr>
        <th>Adres</th><td><?php echo $row['adres']; ?></td>
    </tr>
    <tr>
        <th>Postcode</th><td><?php echo $row['postcode']; ?></td>
    </tr>
    <tr>
        <th>Huisnummer</th><td><?php echo $row['huisnummer']; ?></td>
    </tr>
    <tr>
        <th>Woonplaats</th><td><?php echo $row['woonplaats']; ?></td>
    </tr>
    <tr>
        <th>Telefoon vast</th><td><?php echo $row['telefoon_vast']; ?></td>
    </tr>
    <tr>
        <th>Telefoon mobiel</th><td><?php echo $row['telefoon_mobiel']; ?></td>
    </tr>
    <tr>
        <th>E-mailadres</th><td><?php echo $row['emailadres']; ?></td>
    </tr>
    <tr>
        <th>Lid vanaf</th><td><?php echo $row['ingangsdatum']; ?></td>
    </tr>
</table>
</div>
<div class="col-md-4">
<table class="table table-striped table-condensed">
    <tr>
        <th>Geslacht</th><td><?php echo $row['geslacht']; ?></td>
    </tr>
    <tr>
        <th>Nieuwsbrief</th><td><?php if ($row['nieuwsbrief'] === '1') {echo 'ja';} else if ($row['nieuwsbrief'] === '0') {echo 'nee';} else {echo 'n/a';} ?></td>
    </tr>
    <tr>
        <th>Vrijwilliger</th><td><?php if ($row['vrijwilliger'] === '1') {echo 'ja';} else if ($row['vrijwilliger'] === '0') {echo 'nee';} else {echo 'n/a';} ?></td>
    </tr>
    <tr>
        <th>Licht / geluid</th><td><?php if ($row['vrijwilligeroptie1'] === '1') {echo 'ja';} else if ($row['vrijwilligeroptie1'] === '0') {echo 'nee';} else {echo 'n/a';} ?></td>
    </tr>
    <tr>
        <th>Bar</th><td><?php if ($row['vrijwilligeroptie2'] === '1') {echo 'ja';} else if ($row['vrijwilligeroptie2'] === '0') {echo 'nee';} else {echo 'n/a';} ?></td>
    </tr>
    <tr>
        <th>Schoonmaken / klussen</th><td><?php if ($row['vrijwilligeroptie3'] === '1') {echo 'ja';} else if ($row['vrijwilligeroptie3'] === '0') {echo 'nee';} else {echo 'n/a';} ?></td>
    </tr>
    <tr>
        <th>Promotie / flyeren</th><td><?php if ($row['vrijwilligeroptie4'] === '1') {echo 'ja';} else if ($row['vrijwilligeroptie4'] === '0') {echo 'nee';} else {echo 'n/a';} ?></td>
    </tr>
    <tr>
        <th>Organisatie evenementen</th><td><?php if ($row['vrijwilligeroptie5'] === '1') {echo 'ja';} else if ($row['vrijwilligeroptie5'] === '0') {echo 'nee';} else {echo 'n/a';} ?></td>
    </tr>
</table>
<table class="table table-striped table-condensed">
    <tr>
        <th>CreationDate</th><td><?php echo $row['CreationDate']; ?></td>
    </tr>
    <tr>
        <th>ModificationDate</th><td><?php echo $row['ModificationDate']; ?></td>
    </tr>
</table>
</div>

<table class="table table-striped table-condensed">
    <tr>
        <th>Betalingswijze</th><td><?php echo $row['betalingswijze']; ?></td>
    </tr>
    <tr>
        <th>IBAN</th><td><?php echo $row['iban']; ?></td>
    </tr>
    <tr>
        <th>Machtigingskenmerk</th><td><?php echo $row['machtigingskenmerk']; ?></td>
    </tr>
    <tr>
        <th>Incasso gelukt</th><td><?php echo $row['incasso_gelukt']; ?></td>
    </tr>
    <tr>
        <th>Opmerking</th><td><?php echo $row['opmerking']; ?></td>
    </tr>
</table>

</div>