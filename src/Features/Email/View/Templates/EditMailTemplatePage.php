<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_EDIT_MAIL_TEMPLATE');
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
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
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages'));

        $attachments = $mailTemplate->attachments();
        if ($attachments->isEmpty()) { ?>
            <p>Dit bericht heeft geen bijlagen</p>
        <?php } else { ?>
            <form method="post" action="/Email/EditTemplate/Attachments/Delete">
                <input type="hidden" name="template_id" value="<?= $mailTemplate->id ?>">
                <div class="mb-3">
                    <label for="currentattachments">Bijlage(s)</label>
                    <ul id="currentattachments">
                        <?php foreach ($attachments as $attachment) { ?>
                            <li>
                                <input type="checkbox" name="id[]" id="checkbox" value="<?= $attachment->id ?>">
                                <?= $attachment->path . $attachment->name . '.' . ltrim((string) $attachment->extension, '.') ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <button type="submit" class="btn btn-danger">Verwijderen</button>
            </form>
        <?php } ?>
        <hr>
        <form method="post" action="/Email/EditTemplate/Attachment" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="attachment_file">Upload een bijlage:</label>
                <input type="file" name="attachment_file" required/>
            </div>
            <input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
            <input type="hidden" name="template_id" value="<?= $mailTemplate->id ?>">
            <button type="submit">Bijlage uploaden</button>
        </form>
        <hr>
        <form method="post" action="/Email/EditTemplate">
            <input type="hidden" name="id" value="<?= $mailTemplate->id ?>">
            <button type="submit" class="btn btn-primary float-end">Opslaan</button>
            <div class="mb-3">
                <label for="subject">Onderwerp</label>
                <input type="text" name="subject" class="form-control" id="subject" placeholder="Onderwerp"
                       value="<?= $mailTemplate->subject ?>">
            </div>
            <div class="mb-3">
                <label for="body">Bericht</label>
                <textarea id="mytextarea" name="body" cols="50" rows="15" required>
                        <?= $mailTemplate->body ?>
                    </textarea>
            </div>
        </form>
        <hr>
        <p>Beschikbare placeholders voor signup: username, sitename, activatelink, activateformlink, confcode</p>
        <p>Beschikbare placeholders voor password reset: USERNAME, RESETLINK, SITENAME</p>
    </div>

<?= $this->end();
