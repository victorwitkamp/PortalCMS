<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

$pageName = 'Lidmaatschap van ' . $member->voornaam . ' ' . $member->achternaam;
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>

    <div class="container">
        <form method="post" action="/Membership/Delete">
            <a href="/Membership" class="btn btn-sm btn-primary">
                <span class="fa fa-arrow-left"></span>
            </a>
            <a href="Edit?Id=<?= $member->id ?>" class="btn btn-sm btn-warning">
                <span class="fa fa-edit"></span>
            </a>
            <input name="id" type="hidden" value="<?= $member->id ?>">
            <button type="submit"
                    onclick="return confirm('Weet je zeker dat je <?= $member->voornaam ?> <?= $member->achternaam ?> wilt verwijderen?')"
                    class="btn btn-sm btn-danger">
                <i class="far fa-trash-alt"></i>
            </button>
        </form>
        <hr>
        <?php //var_dump($member);?>

        <div class="row">
            <div class="col-md-8">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th>ID</th>
                        <td><?= $member->id ?></td>
                    </tr>
                    <tr>
                        <th>Jaar van lidmaatschap</th>
                        <td><?= $member->jaarlidmaatschap ?></td>
                    </tr>
                    <tr>
                        <th>Voorletters</th>
                        <td><?= $member->voorletters ?></td>
                    </tr>
                    <tr>
                        <th>Voornaam</th>
                        <td><?= $member->voornaam ?></td>
                    </tr>
                    <tr>
                        <th>Achternaam</th>
                        <td><?= $member->achternaam ?></td>
                    </tr>
                    <tr>
                        <th>Geboortedatum</th>
                        <td><?= $member->geboortedatum ?></td>
                    </tr>
                    <tr>
                        <th>Adres</th>
                        <td><?= $member->adres ?></td>
                    </tr>
                    <tr>
                        <th>Postcode</th>
                        <td><?= $member->postcode ?></td>
                    </tr>
                    <tr>
                        <th>Huisnummer</th>
                        <td><?= $member->huisnummer ?></td>
                    </tr>
                    <tr>
                        <th>Woonplaats</th>
                        <td><?= $member->woonplaats ?></td>
                    </tr>
                    <tr>
                        <th>Telefoon vast</th>
                        <td><?= $member->telefoon_vast ?></td>
                    </tr>
                    <tr>
                        <th>Telefoon mobiel</th>
                        <td><?= $member->telefoon_mobiel ?></td>
                    </tr>
                    <tr>
                        <th>E-mailadres</th>
                        <td><?= $member->emailadres ?></td>
                    </tr>
                    <tr>
                        <th>Lid vanaf</th>
                        <td><?= $member->ingangsdatum ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th>Geslacht</th>
                        <td><?= $member->geslacht ?></td>
                    </tr>
                    <tr>
                        <th>Nieuwsbrief</th>
                        <td><?php
                            if ($member->nieuwsbrief === true) {
                                echo 'ja';
                            } elseif ($member->nieuwsbrief === false) {
                                echo 'nee';
                            } else {
                                echo 'n/a';
                            } ?></td>
                    </tr>
                    <tr>
                        <th>Vrijwilliger</th>
                        <td><?php
                            if ($member->vrijwilliger === true) {
                                echo 'ja';
                            } elseif ($member->vrijwilliger === false) {
                                echo 'nee';
                            } else {
                                echo 'n/a';
                            } ?></td>
                    </tr>
                    <tr>
                        <th>Licht / geluid</th>
                        <td><?php
                            if ($member->vrijwilligeroptie1 === true) {
                                echo 'ja';
                            } elseif ($member->vrijwilligeroptie1 === false) {
                                echo 'nee';
                            } else {
                                echo 'n/a';
                            } ?></td>
                    </tr>
                    <tr>
                        <th>Bar</th>
                        <td><?php
                            if ($member->vrijwilligeroptie2 === true) {
                                echo 'ja';
                            } elseif ($member->vrijwilligeroptie2 === false) {
                                echo 'nee';
                            } else {
                                echo 'n/a';
                            } ?></td>
                    </tr>
                    <tr>
                        <th>Schoonmaken / klussen</th>
                        <td><?php
                            if ($member->vrijwilligeroptie3 === true) {
                                echo 'ja';
                            } elseif ($member->vrijwilligeroptie3 === false) {
                                echo 'nee';
                            } else {
                                echo 'n/a';
                            } ?></td>
                    </tr>
                    <tr>
                        <th>Promotie / flyeren</th>
                        <td><?php
                            if ($member->vrijwilligeroptie4 === true) {
                                echo 'ja';
                            } elseif ($member->vrijwilligeroptie4 === false) {
                                echo 'nee';
                            } else {
                                echo 'n/a';
                            } ?></td>
                    </tr>
                    <tr>
                        <th>Organisatie evenementen</th>
                        <td><?php
                            if ($member->vrijwilligeroptie5 === true) {
                                echo 'ja';
                            } elseif ($member->vrijwilligeroptie5 === false) {
                                echo 'nee';
                            } else {
                                echo 'n/a';
                            } ?></td>
                    </tr>
                </table>
                <table class="table table-striped table-condensed">
                    <tr>
                        <th>CreationDate</th>
                        <td><?= $member->creationDate ?></td>
                    </tr>
                    <tr>
                        <th>ModificationDate</th>
                        <td><?= $member->modificationDate ?></td>
                    </tr>
                </table>
            </div>
            <table class="table table-striped table-condensed">
                <tr>
                    <th>Betalingswijze</th>
                    <td><?= $member->betalingswijze ?></td>
                </tr>
                <tr>
                    <th>IBAN</th>
                    <td><?= $member->iban ?></td>
                </tr>
                <tr>
                    <th>Machtigingskenmerk</th>
                    <td><?= $member->machtigingskenmerk ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?= $member->status ?></td>
                </tr>
                <!--<tr>
                <th>Incasso gelukt</th><td><?php //echo $member->incasso_gelukt;
                ?></td>
            </tr>-->
                <tr>
                    <th>Opmerking</th>
                    <td><?= $member->opmerking ?></td>
                </tr>
            </table>
        </div>

    </div>

<?= $this->end();
