<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;
use PortalCMS\Core\Email\Template\EmailTemplatePDOReader;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$templateId = (int) Request::get('id');
$template = EmailTemplatePDOReader::getById($templateId);
if (empty($template)) {
    Redirect::to('Error/NotFound');
}
$pageName = Text::get('TITLE_EDIT_MAIL_TEMPLATE');
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<script src='https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=y6xawmw19w565wdi90wrtlow2ll6498emv0fozfrtrt7vb4y'></script>
<script>
    tinymce.init({
        selector: '#mytextarea',
        plugins: 'advlist autolink link image lists charmap print preview'
    });
</script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

<div class="container">
    <div class="row mt-5">
        <div class="col-sm-8">
            <h1><?= $pageName ?></h1>
        </div>
        <div class="col-sm-4"></div>
    </div>
    <hr>
    <?php Alert::renderFeedbackMessages();

    $attachments = EmailAttachmentMapper::getByTemplateId($templateId);
    if (empty($attachments)) { ?>
        <p>Dit bericht heeft geen bijlagen</p>
    <?php } else { ?>
        <form method="post">
            <div class="form-group">
                <label for="currentattachments">Bijlage(s)</label>
                <ul id="currentattachments">
                    <?php foreach ($attachments as $attachment) { ?>
                        <li>
                            <input type="checkbox" name="id[]" id="checkbox" value="<?= $attachment->id ?>">
                            <?= $attachment->path . $attachment->name . $attachment->extension ?>
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
            <input type="file" name="attachment_file" required />
        </div>
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
        <input type="submit" name="uploadAttachment" value="Bijlage uploaden" />
    </form>
    <hr>
    <form method="post">
        <input type="hidden" name="id" value="<?= $template->id ?>">
        <input type="submit" class="btn btn-primary float-right" name="editTemplateAction" />
        <div class="form-group">
            <label for="subject">Onderwerp</label>
            <input type="text" name="subject" class="form-control" id="subject" placeholder="Onderwerp" value="<?= $template->subject ?>">
        </div>
        <div class="form-group">
            <label for="body">Bericht</label>
            <textarea id="mytextarea" name="body" cols="50" rows="15" required>
                        <?= $template->body ?>
                    </textarea>
        </div>
    </form>
    <hr>
    <p>Beschikbare placeholders voor signup: username, sitename, activatelink, activateformlink, confcode</p>
    <p>Beschikbare placeholders voor password reset: USERNAME, RESETLINK, SITENAME</p>
</div>

<?= $this->end();
