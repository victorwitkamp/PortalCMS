<?php
$pageName = 'Evenement bewerken';
$allowEdit = false;
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
require_once DIR_INCLUDES.'functions.php';

if ($row = Event::getEventById($_GET['id'])) {
    $allowEdit = true;
    $pageName = 'Evenement '.$row ['title'].' bewerken';
} else {
    Session::add('feedback_negative', "Geen resultaten voor opgegeven event ID.");
    Redirect::Error();
}

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
                <h3><?php echo $pageName ?></h3>
            </div>
        </div>
        <hr>
        <div class="container">
            <?php Alert::renderFeedbackMessages(); ?>
            <form method="post" validate=true>
                <div class="form-group form-group-sm row">
                    <div class="col-sm-12">
                        <label class="control-label">Naam van het evenement</label>
                        <input type="text" name="title" value="<?php if ($allowEdit) { echo $row ['title']; } ?>" class="form-control input-sm" placeholder="" required <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                    </div>
                </div>
                <div class="form-group form-group-sm row">
                    <div class="col-sm-6">
                        <label class="control-label">Start</label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                            <input type="text" name="start_event" value="<?php if ($allowEdit) { echo $row ['start_event']; } ?>" class="form-control input-sm  datetimepicker-input" data-target="#datetimepicker1" required <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Einde</label>
                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                            <input type="text" name="end_event" value="<?php if ($allowEdit) { echo $row ['end_event']; } ?>" class="form-control input-sm  datetimepicker-input" data-target="#datetimepicker2" required <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm row">
                    <div class="col-sm-12">
                        <label class="control-label">Beschrijving</label>
                        <input type="text" name="description" value="<?php if ($allowEdit) { echo $row ['description']; } ?>" class="form-control input-sm" placeholder="" required <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                    </div>
                </div>
                <hr>
                <div class="form-group form-group-sm">
                    <input type="hidden" name="id" value="<?php if ($allowEdit) { echo $row ['id']; } ?>">
                    <input type="submit" name="updateEvent" class="btn btn-sm btn-primary" value="Opslaan" <?php if (!$allowEdit) { echo 'disabled'; } ?>>
                    <a href="index.php" class="btn btn-sm btn-danger">Annuleren</a>
                </div>
            </form>
        </div>
    </div>
</main>
<?php require DIR_INCLUDES.'footer.php'; ?>
</body>
</html>