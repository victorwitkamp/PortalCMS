<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Auth::checkPrivilege("mail-templates")) {
    Redirect::permissionError();
    die();
}
$pageName = Text::get('TITLE_MAIL_TEMPLATES');

require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>
<script src='https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=y6xawmw19w565wdi90wrtlow2ll6498emv0fozfrtrt7vb4y'></script>
<script>
tinymce.init({
selector: '#mytextarea',
plugins : 'advlist autolink link image lists charmap print preview'
});
</script>

</head>
<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?php echo $pageName ?></h1></div>
                <div class="col-sm-4">
                    <!-- <a href="#" class="btn btn-info float-right"><span class="fa fa-plus"></span> Nieuwe template</a> -->
                </div>
            </div>
            <hr>
            <?php Alert::renderFeedbackMessages(); ?>
            <h3>Nieuw template</h3>
            <div class="form-group row">
            <form method="post">
            <textarea id="mytextarea" name="body" cols="50" rows="15" required>
            </textarea>
            <input type="text" name="subject"/>
            <input type="submit" name="newtemplate"/>
            </form>
            </div>
            <p>Beschikbare placeholders voor signup: username, sitename, activatelink, activateformlink, confcode</p>
            <p>Beschikbare placeholders voor password reset: USERNAME, RESETLINK, SITENAME</p>
        </div>
    </div>
</main>
<?php View::renderFooter(); ?>
</body>
</html>