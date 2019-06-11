<?php
$pageName = 'Factuur toevoegen';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Auth::checkPrivilege("rental-invoices")) {
    Redirect::permissionerror();
    die();
}
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();

PortalCMS_JS_headJS();

PortalCMS_JS_JQuery_Simple_validator(); ?>
</head>
<body>
    <?php require DIR_INCLUDES.'nav.php'; ?>
    <main>
        <div class="content">
            <div class="container">
                <div class="row mt-5">
                    <h1><?php echo $pageName ?></h1>
                </div>

            <hr>

                <?php Alert::renderFeedbackMessages(); ?>
                <p>Zorg ervoor dat de bedragen voor de huur van de ruimte en van een eventuele kast reeds ingevuld zijn in het contract.</p>
                <form method="post" validate=true>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?php echo Text::get('YEAR'); ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="year" class="form-control" value="2019">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Contract</label>
                        <div class="col-sm-10">
                            <select name="contract_id" class="form-control">
                                <?php foreach (ContractMapper::get() as $row): ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['bandcode'].': '.$row['band_naam']; ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?php echo Text::get('MONTH'); ?></label>
                        <div class="col-sm-10">
                            <select name="month" class="form-control">
                                <option value="01"><?php echo Text::get('MONTH_01'); ?></option>
                                <option value="02"><?php echo Text::get('MONTH_02'); ?></option>
                                <option value="03"><?php echo Text::get('MONTH_03'); ?></option>
                                <option value="04"><?php echo Text::get('MONTH_04'); ?></option>
                                <option value="05"><?php echo Text::get('MONTH_05'); ?></option>
                                <option value="06"><?php echo Text::get('MONTH_06'); ?></option>
                                <option value="07"><?php echo Text::get('MONTH_07'); ?></option>
                                <option value="08"><?php echo Text::get('MONTH_08'); ?></option>
                                <option value="09"><?php echo Text::get('MONTH_09'); ?></option>
                                <option value="10"><?php echo Text::get('MONTH_10'); ?></option>
                                <option value="11"><?php echo Text::get('MONTH_11'); ?></option>
                                <option value="12"><?php echo Text::get('MONTH_12'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Factuurdatum</label>
                        <div class="col-sm-10">
                            <input type="text" name="factuurdatum" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Vervaldatum</label>
                        <div class="col-sm-10">
                            <input type="text" name="vervaldatum" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    <input type="submit" name="createInvoice" class="btn btn-primary" value="Opslaan">
                    <a href="index.php" class="btn btn-danger">Annuleren</a>
                </form>

            </div>

        </div>
    </main>
    <?php View::renderFooter(); ?>
</body>

</html>