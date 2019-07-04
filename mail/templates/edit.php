<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Auth::checkPrivilege("mail-templates")) {
    Redirect::permissionError();
    die();
}
$template = MailTemplate::getTemplateById(Request::get('id'));
$pageName = Text::get('TITLE_MAIL_TEMPLATES').': id = '.$template['id'];

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
            <h3>Bewerken</h3>
            <div class="form-group row">
            Attachments:<br>
            <?php
                $attachments = MailAttachmentMapper::getByTemplateId(Request::get('id'));
                if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    echo $attachment['path'].$attachment['name'].$attachment['extension'];
                    echo '<br>';
                }
                }
            ?>

            </div>
<div class="form-group row"><form method="post" enctype="multipart/form-data">
                <label for="avatar_file">
                Upload een bijlage:
                </label>
                <input type="file" name="attachment_file" required />
                <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
                <input type="submit" name="uploadAttachment" value="Bijlage uploaden" />
            </form></div>

            <div class="form-group row">
                <textarea id="mytextarea" name="body" cols="50" rows="15" required>
                <?php
                echo htmlspecialchars($template['body']); ?>
                </textarea>
            </div>
            <p>Beschikbare placeholders voor signup: username, sitename, activatelink, activateformlink, confcode</p>
            <p>Beschikbare placeholders voor password reset: USERNAME, RESETLINK, SITENAME</p>
        </div>
    </div>
</main>
<?php View::renderFooter(); ?>
</body>
</html>