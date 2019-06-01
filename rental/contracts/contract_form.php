<!-- <form method="post" validate=true> -->
<form method="post">

    <div class="row">
        <div class="col-md-12">



            <div class="form-group row">
                <div class="col-md-8">
                    <label class="col-form-label">band_naam</label>
                    <input type="text" name="band_naam" class="form-control form-control-sm" placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['band_naam']; } ?>" required
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">bandcode</label>
                    <input type="text" name="bandcode" class="form-control form-control-sm" placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['bandcode']; } ?>" required
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
            </div>
            <hr>

            <h3>Kosten</h3>
            <div class="form-group row">
                <div class="col-md-3">
                    <label class="col-form-label">Ruimte (per maand)</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-euro-sign"></i>
                            </div>
                            <input type="text" name="kosten_ruimte" class="form-control form-control-sm" placeholder=""
                                value="<?php if ($pageType === 'edit') { echo $row ['kosten_ruimte']; } ?>"
                                <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="col-form-label">Kast (per maand)</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-euro-sign"></i>
                            </div>
                            <input type="text" name="kosten_kast" class="form-control form-control-sm" placeholder=""
                                value="<?php if ($pageType === 'edit') { echo $row ['kosten_kast']; } ?>"
                                <?php if (!$allowEdit) { echo 'disabled'; } ?>>
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
                            <input type="text" name="kosten_totaal" class="form-control form-control-sm" placeholder=""
                                value="<?php if ($pageType === 'edit') { echo $row ['kosten_totaal']; } ?>" disabled>
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
                            <input type="text" name="kosten_borg" class="form-control form-control-sm" placeholder=""
                                value="<?php if ($pageType === 'edit') { echo $row ['kosten_borg']; } ?>"
                                <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">

            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <label class="col-form-label">contract_ingangsdatum</label>
                    <div class="form-group date" id="datetimepicker2" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" name="contract_ingangsdatum"
                                class="form-control form-control-sm  datetimepicker-input"
                                data-target="#datetimepicker2"
                                value="<?php if ($pageType === 'edit') { echo $row ['contract_ingangsdatum']; } ?>"
                                <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="col-form-label">contract_einddatum</label>
                    <div class="form-group date" id="datetimepicker3" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" name="contract_einddatum"
                                class="form-control form-control-sm  datetimepicker-input"
                                data-target="#datetimepicker3"
                                value="<?php if ($pageType === 'edit') { echo $row ['contract_einddatum']; } ?>"
                                <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="col-form-label">contract_datum</label>
                    <div class="form-group date" id="datetimepicker4" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" name="contract_datum"
                                class="form-control form-control-sm  datetimepicker-input"
                                data-target="#datetimepicker4"
                                value="<?php if ($pageType === 'edit') { echo $row ['contract_datum']; } ?>"
                                <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                        </div>
                    </div>
                </div>

            </div>

            <hr>
            <h3>Gegevens bandleider</h3>
            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">bandleider_naam</label>
                    <input type="text" name="bandleider_naam" class="form-control form-control-sm" placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['bandleider_naam']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label class="col-form-label">bandleider_adres</label>
                    <input type="text" name="bandleider_adres" class="form-control form-control-sm"
                        placeholder="Voorbeeldadres 123"
                        value="<?php if ($pageType === 'edit') { echo $row ['bandleider_adres']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
                <div class="col-md-2">
                    <label class="col-form-label">bandleider_postcode</label>
                    <input type="text" name="bandleider_postcode" class="form-control form-control-sm" maxlength="6"
                        placeholder="1234AB"
                        value="<?php if ($pageType === 'edit') { echo $row ['bandleider_postcode']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>

                <div class="col-md-4">
                    <label class="col-form-label">bandleider_woonplaats</label>
                    <input type="text" name="bandleider_woonplaats" class="form-control form-control-sm"
                        placeholder="Barendrecht"
                        value="<?php if ($pageType === 'edit') { echo $row ['bandleider_woonplaats']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">bandleider_geboortedatum</label>
                    <div class="form-group date" id="datetimepicker1" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" name="bandleider_geboortedatum" class="form-control datetimepicker-input"
                                data-target="#datetimepicker1"
                                value="<?php if ($pageType === 'edit') { echo $row ['bandleider_geboortedatum']; } ?>"
                                <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label class="col-form-label">bandleider_telefoonnummer1</label>
                    <input type="text" name="bandleider_telefoonnummer1" class="form-control form-control-sm"
                        placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['bandleider_telefoonnummer1']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
                <div class="col-md-6">
                    <label class="col-form-label">bandleider_telefoonnummer2</label>
                    <input type="text" name="bandleider_telefoonnummer2" class="form-control form-control-sm"
                        placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['bandleider_telefoonnummer2']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">bandleider_email</label>
                    <input type="text" name="bandleider_email" class="form-control form-control-sm" placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['bandleider_email']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">bandleider_bsn</label>
                    <input type="text" name="bandleider_bsn" class="form-control form-control-sm" placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['bandleider_bsn']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
            </div>
            <hr>
            <h3>Oefenruimte</h3>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Oefenruimte nr.</label>
                <div class="col-sm-10">

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="huur_oefenruimte_nr" value="1"
                            <?php if ($pageType === 'edit' AND $row['huur_oefenruimte_nr'] === '1') { echo 'checked'; } ?>>
                        <label class="form-check-label" for="exampleRadios1">
                            Oefenruimte 1
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="huur_oefenruimte_nr" value="2"
                            <?php if ($pageType === 'edit' AND $row['huur_oefenruimte_nr'] === '2') { echo 'checked'; } ?>>
                        <label class="form-check-label" for="exampleRadios2">
                            Oefenruimte 2
                        </label>
                    </div>

                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><?php echo Text::get('DAY'); ?></label>
                <div class="col-sm-10">
<?php if ($pageType === 'edit') { ?><input type="text" value="<?php echo $row['huur_dag']; ?>" disabled> <?php } ?>
                    <select name="huur_dag" class="form-control" <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                        <option>Selecteer een dag....</option>
                        <option value="<?php echo Text::get('DAY_01'); ?>"><?php echo Text::get('DAY_01'); ?></option>
                        <option value="<?php echo Text::get('DAY_02'); ?>"><?php echo Text::get('DAY_02'); ?></option>
                        <option value="<?php echo Text::get('DAY_03'); ?>"><?php echo Text::get('DAY_03'); ?></option>
                        <option value="<?php echo Text::get('DAY_04'); ?>"><?php echo Text::get('DAY_04'); ?></option>
                        <option value="<?php echo Text::get('DAY_05'); ?>"><?php echo Text::get('DAY_05'); ?></option>
                        <option value="<?php echo Text::get('DAY_06'); ?>"><?php echo Text::get('DAY_06'); ?></option>
                        <option value="<?php echo Text::get('DAY_07'); ?>"><?php echo Text::get('DAY_07'); ?></option>
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-md-6">
                    <label class="col-form-label">huur_start</label>
                    <input type="text" name="huur_start" class="form-control form-control-sm" placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['huur_start']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
                <div class="col-md-6">
                    <label class="col-form-label">huur_eind</label>
                    <input type="text" name="huur_einde" class="form-control form-control-sm" placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['huur_einde']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">huur_kast_nr</label>
                    <input type="text" name="huur_kast_nr" class="form-control form-control-sm" placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['huur_kast_nr']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">beuk_vertegenwoordiger</label>
                    <input type="text" name="beuk_vertegenwoordiger" class="form-control form-control-sm" placeholder=""
                        value="<?php if ($pageType === 'edit') { echo $row ['beuk_vertegenwoordiger']; } ?>"
                        <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                </div>
            </div>

        </div>

    </div>



    <div class="row">
        <div class="col-md-12">
            <hr>
            <p>* = verplicht veld.</p>
            <div class="form-group row">
                <div class="col-md-6">
                    <label class="col-form-label">&#xA0;</label>
                    <input type="hidden" name="id" value="<?php if ($page = 'edit') { echo $row ['id']; } ?>">
                    <input type="submit"
                        name="<?php if ($pageType === 'edit') { echo 'updateContract'; } else { echo 'newContract'; }?>"
                        class="btn btn-sm btn-primary" value="Opslaan" <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                    <a href="contracten.php" class="btn btn-sm btn-danger">Annuleren</a>
                </div>
            </div>
        </div>
    </div>

</form>