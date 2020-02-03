<?php
declare(strict_types=1);

use PortalCMS\Core\View\Text;

?>
<div class="row">
    <div class="col-md-8">
        <div class="form-group row">
            <div class="col-md-8">
                <label class="col-form-label">Huurder</label>
                <input type="text" class="form-control form-control-sm" value="<?= $contract->band_naam ?>" required disabled>
            </div>
            <div class="col-md-4">
                <label class="col-form-label">Klantcode</label>
                <input type="text" class="form-control form-control-sm" value="<?= $contract->bandcode ?>" required disabled>
            </div>
        </div>

        <h3>Contactpersoon</h3>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label class="col-form-label">Naam</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_naam ?>" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <label class="col-form-label">Adres</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_adres ?>" disabled>
                </div>
                <div class="col-md-3">
                    <label class="col-form-label">Postcode</label>
                    <input type="text" class="form-control form-control-sm" maxlength="6" value="<?= $contract->bandleider_postcode ?>" disabled>
                </div>

                <div class="col-md-4">
                    <label class="col-form-label">Woonplaats</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_woonplaats ?>" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="col-form-label">Geboortedatum</label>
                    <div class="form-group date" id="datetimepicker1" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" value="<?= $contract->bandleider_geboortedatum ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="col-form-label">Telefoonnummer 1</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_telefoonnummer1 ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label class="col-form-label">Telefoonnummer 2</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_telefoonnummer2 ?>" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="col-form-label">E-mail</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_email ?>" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="col-form-label">BSN</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_bsn ?>" disabled>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label class="col-form-label">beuk_vertegenwoordiger</label>
                <input type="text" class="form-control form-control-sm" value="<?= $contract->beuk_vertegenwoordiger ?>" disabled>
            </div>
        </div>
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
