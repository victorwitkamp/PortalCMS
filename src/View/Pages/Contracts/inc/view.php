<?php
declare(strict_types=1);

use PortalCMS\Core\View\Text;

?>
<div class="row">
    <div class="col-md-8">
        <h3>Algemeen</h3>
        <table class="table table-striped table-condensed">
            <tbody>
            <tr>
                <th>Huurder</th>
                <td><?= $contract->band_naam ?></td>
            </tr>
            <tr>
                <th>Klantcode</th>
                <td><?= $contract->bandcode ?></td>
            </tr>
            <tr>
                <th>Vertegenwoordiger de Beuk</th>
                <td><?= $contract->beuk_vertegenwoordiger ?></td>
            </tr>
            </tbody>
        </table>
        <h3>Contactpersoon</h3>
        <table class="table table-striped table-condensed">
            <tbody>
            <tr>
                <th>Naam</th>
                <td><?= $contract->bandleider_naam ?></td>
            </tr>
            <tr>
                <th>Adres</th>
                <td><?= $contract->bandleider_adres ?></td>
            </tr>
            <tr>
                <th>Postcode</th>
                <td><?= $contract->bandleider_postcode ?></td>
            </tr>
            <tr>
                <th>Woonplaats</th>
                <td><?= $contract->bandleider_woonplaats ?></td>
            </tr>
            <tr>
                <th>Geboortedatum</th>
                <td><?= $contract->bandleider_geboortedatum ?></td>
            </tr>
            <tr>
                <th>Telefoonnummer 1</th>
                <td><?= $contract->bandleider_telefoonnummer1 ?></td>
            </tr>
            <tr>
                <th>Telefoonnummer 2</th>
                <td><?= $contract->bandleider_telefoonnummer2 ?></td>
            </tr>
            <tr>
                <th>E-mailadres</th>
                <td><?= $contract->bandleider_email ?></td>
            </tr>
            <tr>
                <th>BSN</th>
                <td><?= $contract->bandleider_bsn ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <h3>Contract datum</h3>
        <table class="table table-striped table-condensed">
            <tbody>
                <tr>
                    <th>Ingangsdatum</th>
                    <td><?= $contract->contract_ingangsdatum ?></td>
                </tr>
                <tr>
                    <th>Einddatum</th>
                    <td><?= $contract->contract_einddatum ?></td>
                </tr>
                <tr>
                    <th>Contractdatum</th>
                    <td><?= $contract->contract_datum ?></td>
                </tr>
            </tbody>
        </table>
                <h3>Oefenruimte</h3>
                <table class="table table-striped table-condensed">
                    <tbody>
                        <tr>
                            <th>Ruimte</th>
                            <td><?= $contract->huur_oefenruimte_nr ?></td>
                        </tr>
                        <tr>
                            <th><?= Text::get('DAY') ?></th>
                            <td><?= $contract->huur_dag ?></td>
                        </tr>
                        <tr>
                            <th>huur_start</th>
                            <td><?= $contract->huur_start ?></td>
                        </tr>
                        <tr>
                            <th>huur_eind</th>
                            <td><?= $contract->huur_einde ?></td>
                        </tr>
                        <tr>
                            <th>huur_kast_nr</th>
                            <td><?= $contract->huur_kast_nr ?></td>
                        </tr>
                    </tbody>
                </table>
                <h3>Kosten</h3>
                <h4>Maandelijks</h4>
                <table class="table table-striped table-condensed">
                    <tbody>
                        <tr>
                            <th>Ruimte</th>
                            <td><?= $contract->kosten_ruimte ?></td>
                        </tr>
                        <tr>
                            <th>Kast</th>
                            <td><?= $contract->kosten_kast ?></td>
                        </tr>
                        <tr>
                            <th>Totaal</th>
                            <td><?= $contract->kosten_totaal ?></td>
                        </tr>
                    </tbody>
                </table>

                <h4>Eenmalig</h4>
                <table class="table table-striped table-condensed">
                    <tbody>
                        <tr>
                            <th>Borg</th>
                            <td><?= $contract->kosten_borg ?></td>
                        </tr>
                    </tbody>
                </table>

    </div>
</div>
