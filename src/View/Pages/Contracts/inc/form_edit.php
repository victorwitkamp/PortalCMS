<?php

use PortalCMS\Core\View\Text;

?>
<form method="post" validate=true>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-md-8">
                    <label class="col-form-label">Huurder</label>
                    <input type="text" name="band_naam" class="form-control form-control-sm" value="<?= $contract->band_naam ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">bandcode</label>
                    <input type="text" name="bandcode" class="form-control form-control-sm" value="<?= $contract->bandcode ?>" required>
                </div>
            </div>
            <div class="form-group">
                <h3>Kosten</h3>
                <div class="row">
                    <div class="col-md-3">
                        <label class="col-form-label">Ruimte (per maand)</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="fas fa-euro-sign"></i>
                                </div>
                                <input type="text" name="kosten_ruimte" class="form-control form-control-sm" value="<?= $contract->kosten_ruimte ?>"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">Kast (per maand)</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="fas fa-euro-sign"></i>
                                </div>
                                <input type="text" name="kosten_kast" class="form-control form-control-sm" value="<?= $contract->kosten_kast ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">Totaal (per maand)</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="fas fa-euro-sign"></i>
                                </div>
                                <input type="text" name="kosten_totaal" class="form-control form-control-sm" value="<?= $contract->kosten_totaal ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">Borg (eenmalig)</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="fas fa-euro-sign"></i>
                                </div>
                                <input type="text" name="kosten_borg" class="form-control form-control-sm" value="<?= $contract->kosten_borg ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label class="col-form-label">Ingangsdatum</label>
                        <div class="form-group date" id="datetimepicker2" data-target-input="nearest">
                            <div class="input-group">
                                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                <input type="text" name="contract_ingangsdatum" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2" value="<?= $contract->contract_ingangsdatum ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">Einddatum</label>
                        <div class="form-group date" id="datetimepicker3" data-target-input="nearest">
                            <div class="input-group">
                                <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                <input type="text" name="contract_einddatum" class="form-control form-control-sm  datetimepicker-input" data-target="#datetimepicker3" value="<?= $contract->contract_einddatum ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">Contractdatum</label>
                        <div class="form-group date" id="datetimepicker4" data-target-input="nearest">
                            <div class="input-group">
                                <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                <input type="text" name="contract_datum" class="form-control form-control-sm  datetimepicker-input" data-target="#datetimepicker4" value="<?= $contract->contract_datum ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Gegevens contactpersoon</h3>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label class="col-form-label">Naam</label>
                        <input type="text" name="bandleider_naam" class="form-control form-control-sm" value="<?= $contract->bandleider_naam ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-form-label">Adres</label>
                        <input type="text" name="bandleider_adres" class="form-control form-control-sm" placeholder="Voorbeeldadres 123" value="<?= $contract->bandleider_adres ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="col-form-label">Postcode</label>
                        <input type="text" name="bandleider_postcode" class="form-control form-control-sm" maxlength="6" placeholder="1234AB" value="<?= $contract->bandleider_postcode ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="col-form-label">Woonplaats</label>
                        <input type="text" name="bandleider_woonplaats" class="form-control form-control-sm" placeholder="Barendrecht" value="<?= $contract->bandleider_woonplaats ?>">
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
                                <input type="text" name="bandleider_geboortedatum" class="form-control datetimepicker-input" data-target="#datetimepicker1" value="<?= $contract->bandleider_geboortedatum ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-form-label">Telefoonnummer 1</label>
                        <input type="text" name="bandleider_telefoonnummer1" class="form-control form-control-sm" value="<?= $contract->bandleider_telefoonnummer1 ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label">Telefoonnummer 2</label>
                        <input type="text" name="bandleider_telefoonnummer2" class="form-control form-control-sm" value="<?= $contract->bandleider_telefoonnummer2 ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="col-form-label">E-mail</label>
                        <input type="text" name="bandleider_email" class="form-control form-control-sm" value="<?= $contract->bandleider_email ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="col-form-label">BSN</label>
                        <input type="text" name="bandleider_bsn" class="form-control form-control-sm" value="<?= $contract->bandleider_bsn ?>">
                    </div>
                </div>
            </div>

            <hr>
            <h3>Oefenruimte</h3>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Oefenruimte nr.</label>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="huur_oefenruimte_nr" value="1" <?php
                        if ($contract->huur_oefenruimte_nr === '1') {
                            echo 'checked';
                        } ?>>
                        <label class="form-check-label">Oefenruimte 1</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="huur_oefenruimte_nr" value="2" <?php
                        if ($contract->huur_oefenruimte_nr === '2') {
                            echo 'checked';
                        } ?>>
                        <label class="form-check-label">Oefenruimte 2</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><?= Text::get('DAY') ?></label>
                <div class="col-sm-10">
                    <select name="huur_dag" class="form-control">
                        <option>Selecteer een dag....</option>
                        <option value="<?= Text::get('DAY_01') ?>" <?php
                        if (Text::get('DAY_01') === $contract->huur_dag) {
                            echo 'selected';
                        } ?>>
                            <?= Text::get('DAY_01') ?>
                        </option>
                        <option value="<?= Text::get('DAY_02') ?>" <?php
                        if (Text::get('DAY_02') === $contract->huur_dag) {
                            echo 'selected';
                        } ?>>
                            <?= Text::get('DAY_02') ?>
                        </option>
                        <option value="<?= Text::get('DAY_03') ?>" <?php
                        if (Text::get('DAY_03') === $contract->huur_dag) {
                            echo 'selected';
                        } ?>>
                            <?= Text::get('DAY_03') ?>
                        </option>
                        <option value="<?= Text::get('DAY_04') ?>" <?php
                        if (Text::get('DAY_04') === $contract->huur_dag) {
                            echo 'selected';
                        } ?>>
                            <?= Text::get('DAY_04') ?>
                        </option>
                        <option value="<?= Text::get('DAY_05') ?>" <?php
                        if (Text::get('DAY_05') === $contract->huur_dag) {
                            echo 'selected';
                        } ?>>
                            <?= Text::get('DAY_05') ?>
                        </option>
                        <option value="<?= Text::get('DAY_06') ?>" <?php
                        if (Text::get('DAY_06') === $contract->huur_dag) {
                            echo 'selected';
                        } ?>>
                            <?= Text::get('DAY_06') ?>
                        </option>
                        <option value="<?= Text::get('DAY_07') ?>" <?php
                        if (Text::get('DAY_07') === $contract->huur_dag) {
                            echo 'selected';
                        } ?>>
                            <?= Text::get('DAY_07') ?>
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label class="col-form-label">huur_start</label>
                    <input type="text" name="huur_start" class="form-control form-control-sm" value="<?= $contract->huur_start ?>">
                </div>
                <div class="col-md-6">
                    <label class="col-form-label">huur_eind</label>
                    <input type="text" name="huur_einde" class="form-control form-control-sm" value="<?= $contract->huur_einde ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">huur_kast_nr</label>
                    <input type="text" name="huur_kast_nr" class="form-control form-control-sm" value="<?= $contract->huur_kast_nr ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">beuk_vertegenwoordiger</label>
                    <input type="text" name="beuk_vertegenwoordiger" class="form-control form-control-sm" value="<?= $contract->beuk_vertegenwoordiger ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <hr>
            <p>* = verplicht veld.</p>
            <input type="hidden" name="id" value="<?= $contract->id ?>">
            <input type="submit" name="updateContract" class="btn btn-sm btn-primary" value="Opslaan">
            <a href="View?id=<?= $contract->id ?>" class="btn btn-sm btn-danger">Annuleren</a>
        </div>
    </div>

</form>
