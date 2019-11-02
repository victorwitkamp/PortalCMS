<?php

use PortalCMS\Core\Email\Template\EmailTemplatePDOReader;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Email\Template\MailTemplateMapper;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('mail-templates');
$reader = new EmailTemplatePDOReader();
$template = $reader->getById(Request::get('id'));
$pageName = Text::get('TITLE_EDIT_MAIL_TEMPLATE');

require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
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
<?php require DIR_INCLUDES . 'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?php echo $pageName ?></h1></div>
                <div class="col-sm-4"></div>
            </div>
            <hr>
            <?php Alert::renderFeedbackMessages();

            $attachments = EmailAttachmentMapper::getByTemplateId(Request::get('id'));
            if (empty($attachments)) { ?>
                <p>Dit bericht heeft geen bijlagen</p>
            <?php } else { ?>
                <form method="post">
                    <div class="form-group">
                        <label for="currentattachments">Bijlage(s)</label>
                        <ul id="currentattachments">
                            <?php foreach ($attachments as $attachment) { ?>
                            <li>
                                <input type="checkbox" name="id[]" id="checkbox" value="<?php echo $attachment['id']; ?>">
                                <?php echo $attachment['path'] . $attachment['name'] . $attachment['extension']; ?>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="submit" class="btn btn-danger" name="deleteMailTemplateAttachments" value="Verwijderen">
                </form>
            <?php } ?>
            <hr>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="attachment_file">Upload een bijlage:</label>
                    <input type="file" name="attachment_file" required/>
                </div>
                <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
                <input type="submit" name="uploadAttachment" value="Bijlage uploaden" />
            </form>
            <hr>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $template['id']; ?>">
                <input type="submit" class="btn btn-primary float-right" name="edittemplate"/>
                <div class="form-group">
                    <label for="subject">Onderwerp</label>
                    <input type="text" name="subject" class="form-control" id="subject" placeholder="Onderwerp" value="<?php echo $template['subject']; ?>">
                </div>
                <div class="form-group">
                    <label for="body">Bericht</label>
                    <textarea id="mytextarea" name="body" cols="50" rows="15" required>
                        <?php echo $template['body']; ?>
                    </textarea>
                </div>
            </form>
            <hr>
            <p>Beschikbare placeholders voor signup: username, sitename, activatelink, activateformlink, confcode</p>
            <p>Beschikbare placeholders voor password reset: USERNAME, RESETLINK, SITENAME</p>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
