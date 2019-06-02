<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_MAIL_TEMPLATES');
Auth::checkAuthentication();
if (!Auth::checkPrivilege("mail-templates")) {
    Redirect::permissionerror();
    die();
}
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
<script>
tinymce.init({
selector: '#mytextarea2',
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
                    <a href="#" class="btn btn-info float-right"><span class="fa fa-plus"></span> Nieuwe template</a>
                </div>
            </div>
            <hr>
            <?php Alert::renderFeedbackMessages(); ?>
            <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th>id</th>
                        <th>type</th>
                        <th>subject</th>
                        <th>body</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (MailTemplate::getTemplates() as $row) {
                        echo '<tr>';
                        echo '<td>'.$row['id'].'</td>';
                        echo '<td>'.$row['type'].'</td>';
                        echo '<td>'.$row['subject'].'</td>';
                        echo '<td>'.$row['body'].'</td><tr>';
                    }
                    ?>
                </tbody>
            </table>
            <h3>Mail templates</h3>
            <div class="form-group row">
                <label class="col-4 col-form-label">Reset password mailtext</label>
                <p>Beschikbare placeholders: USERNAME, RESETLINK, SITENAME</p>
                <div class="col-8">
                    <textarea id="mytextarea" name="MailText_ResetPassword" cols="50" rows="15" required>
                    <?php echo htmlspecialchars(MailTemplate::getStaticMailText('ResetPassword')); ?>
                    </textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">Signup mailtext</label>
                <p>Beschikbare placeholders: username, sitename, activatelink, activateformlink, confcode</p>
                <div class="col-8">
                    <textarea id="mytextarea2" name="MailText_Signup" cols="50" rows="15" required>
                    <?php echo htmlspecialchars(MailTemplate::getStaticMailText('Signup')); ?>
                    </textarea>
                </div>
            </div>
        </div>
    </div>
</main>
<?php View::renderFooter(); ?>
</body>
</html>