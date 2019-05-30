<?php
$pageName = 'Evenement toevoegen';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Permission::hasPrivilege("events")) {
    Redirect::permissionerror();
    die();
}
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_CSS_tempusdominus();
PortalCMS_JS_headJS();
PortalCMS_JS_tempusdominus();
PortalCMS_JS_Datepicker_event();
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
            </div>
            <hr>
            <div class="container">
                <?php Alert::renderFeedbackMessages(); ?>
                <form method="post" validate=true>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label">Naam van het evenement</label>
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label">Start</label>
                            <div class="form-group date" id="datetimepicker1" data-target-input="nearest">
                                <div class="input-group">
                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="start_event" class="form-control form-control-sm  datetimepicker-input" data-target="#datetimepicker1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Einde</label>
                            <div class="form-group date" id="datetimepicker2" data-target-input="nearest">
                                <div class="input-group">
                                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="end_event" class="form-control form-control-sm  datetimepicker-input" data-target="#datetimepicker2" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label">Beschrijving</label>
                            <input type="text" name="description" class="form-control form-control-sm" placeholder="">
                        </div>
                    </div>
                    <hr />
                    <div class="form-group form-group-sm">
                        <input type="text" name="CreatedBy" value="<?php echo Session::get('user_id'); ?>" hidden>
                        <input type="submit" name="addEvent" class="btn btn-sm btn-primary" value="Opslaan">
                        <a href="index.php" class="btn btn-sm btn-danger">Annuleren</a>
                    </div>
                </form>

            </div>

        </div>
    </main>
    <?php require DIR_INCLUDES.'footer.php'; ?>
</body>

</html>