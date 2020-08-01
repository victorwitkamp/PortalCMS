<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Modules\Members\MemberModel;

$pageName = 'Profiel';

$member = MemberModel::getMember((int) Request::get('Id'));
$pageName = 'Lidmaatschap van ' . $member->voornaam . ' ' . $member->achternaam;
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

<div class="container">
    <div class="row mt-5">
        <h1><?= $pageName ?></h1>
    </div>
</div>

<div class="container">
    <form method="post">
        <a href="/Membership" class="btn btn-sm btn-primary">
            <span class="fa fa-arrow-left"></span>
        </a>
        <a href="Edit?Id=<?= $member->id ?>" class="btn btn-sm btn-warning">
            <span class="fa fa-edit"></span>
        </a>
        <input name="id" type="hidden" value="<?= $member->id ?>">
        <button name="deleteMember" type="submit" onclick="return confirm('Weet je zeker dat je <?= $member->voornaam ?> <?= $member->achternaam ?> wilt verwijderen?')" class="btn btn-sm btn-danger">
            <i class="far fa-trash-alt"></i>
        </button>
    </form>
    <hr>
    <?php //var_dump($member); ?>

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
                    <td><?= $member->address->adres ?></td>
                </tr>
                <tr>
                    <th>Postcode</th>
                    <td><?= $member->address->postcode ?></td>
                </tr>
                <tr>
                    <th>Huisnummer</th>
                    <td><?= $member->address->huisnummer ?></td>
                </tr>
                <tr>
                    <th>Woonplaats</th>
                    <td><?= $member->address->woonplaats ?></td>
                </tr>
                <tr>
                    <th>Telefoon vast</th>
                    <td><?= $member->contactDetails->telefoon_vast ?></td>
                </tr>
                <tr>
                    <th>Telefoon mobiel</th>
                    <td><?= $member->contactDetails->telefoon_mobiel ?></td>
                </tr>
                <tr>
                    <th>E-mailadres</th>
                    <td><?= $member->contactDetails->emailadres ?></td>
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
                    if ($member->preferences->nieuwsbrief === 1) {
                        echo 'ja';
                    } elseif ($member->preferences->nieuwsbrief === 0) {
                        echo 'nee';
                    } else {
                        echo 'n/a';
                    } ?></td>
                </tr>
                <tr>
                    <th>Vrijwilliger</th>
                    <td><?php
                    if ($member->preferences->vrijwilliger === 1) {
                        echo 'ja';
                    } elseif ($member->preferences->vrijwilliger === 0) {
                        echo 'nee';
                    } else {
                        echo 'n/a';
                    } ?></td>
                </tr>
                <tr>
                    <th>Licht / geluid</th>
                    <td><?php
                    if ($member->preferences->vrijwilligeroptie1 === 1) {
                        echo 'ja';
                    } elseif ($member->preferences->vrijwilligeroptie1 === 0) {
                        echo 'nee';
                    } else {
                        echo 'n/a';
                    } ?></td>
                </tr>
                <tr>
                    <th>Bar</th>
                    <td><?php
                    if ($member->preferences->vrijwilligeroptie2 === 1) {
                        echo 'ja';
                    } elseif ($member->preferences->vrijwilligeroptie2 === 0) {
                        echo 'nee';
                    } else {
                        echo 'n/a';
                    } ?></td>
                </tr>
                <tr>
                    <th>Schoonmaken / klussen</th>
                    <td><?php
                    if ($member->preferences->vrijwilligeroptie3 === 1) {
                        echo 'ja';
                    } elseif ($member->preferences->vrijwilligeroptie3 === 0) {
                        echo 'nee';
                    } else {
                        echo 'n/a';
                    } ?></td>
                </tr>
                <tr>
                    <th>Promotie / flyeren</th>
                    <td><?php
                    if ($member->preferences->vrijwilligeroptie4 === 1) {
                        echo 'ja';
                    } elseif ($member->preferences->vrijwilligeroptie4 === 0) {
                        echo 'nee';
                    } else {
                        echo 'n/a';
                    } ?></td>
                </tr>
                <tr>
                    <th>Organisatie evenementen</th>
                    <td><?php
                    if ($member->preferences->vrijwilligeroptie5 === 1) {
                        echo 'ja';
                    } elseif ($member->preferences->vrijwilligeroptie5 === 0) {
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
                <td><?= $member->paymentDetails->betalingswijze ?></td>
            </tr>
            <tr>
                <th>IBAN</th>
                <td><?= $member->paymentDetails->iban ?></td>
            </tr>
            <tr>
                <th>Machtigingskenmerk</th>
                <td><?= $member->paymentDetails->machtigingskenmerk ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= $member->paymentDetails->status ?></td>
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
