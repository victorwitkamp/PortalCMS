<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Auth::checkPrivilege("mail-templates")) {
    Redirect::permissionError();
    die();
}
$template = MailTemplateMapper::getTemplateById(Request::get('id'));
$pageName = Text::get('TITLE_EDIT_MAIL_TEMPLATE');

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

            <form method="post">
                <div class="form-group">
                    <label for="subject">Onderwerp</label>
                    <input type="text" name="subject" class="form-control" id="subject" placeholder="Onderwerp" value="<?php echo $template['subject']; ?>">
                </div>
                <div class="form-group">
                    <textarea id="mytextarea" name="body" cols="50" rows="15" required>
                        <?php echo $template['body']; ?>
                    </textarea>
                </div>
                <input type="hidden" name="id" value="<?php echo $template['id']; ?>">
                <input type="submit" class="btn btn-primary" name="edittemplate"/>
            </form>
            <hr>
            <form method="post">
                <div class="form-group">
                    <label for="currentattachments">Bijlage(s)</label>
                    <?php
                        $attachments = MailAttachmentMapper::getByTemplateId(Request::get('id'));
                    if (!empty($attachments)) {
                        echo '<ul id="currentattachments">';
                        foreach ($attachments as $attachment) {
                            echo '<li>';
                            echo '<input type="checkbox" name="id[]" id="checkbox" value="'.$attachment['id'].'">';
                            echo $attachment['path'].$attachment['name'].$attachment['extension'];
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                    ?>
                </div>
                <input type="submit" class="btn btn-danger" name="deleteMailTemplateAttachments" value="Verwijderen">
            </form>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                <label for="attachment_file">Upload een bijlage:</label>
                    <input type="file" name="attachment_file" required />
                    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
                    <input type="submit" name="uploadAttachment" value="Bijlage uploaden" />
                </div>
            </form>
            <hr>
            <p>Beschikbare placeholders voor signup: username, sitename, activatelink, activateformlink, confcode</p>
            <p>Beschikbare placeholders voor password reset: USERNAME, RESETLINK, SITENAME</p>
        </div>
    </div>
</main>
<?php View::renderFooter(); ?>
</body>
</html>
