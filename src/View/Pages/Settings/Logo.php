<?php

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_SITE_LOGO');
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<script src="/dist/bs-custom-file-input/dist/bs-custom-file-input.min.js" async></script>


<?= $this->end(); ?>
<?= $this->push('main-content') ?>


<div class="container">
    <div class="row mt-5">
        <div class="col-sm-12">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <hr>

    <?php Alert::renderFeedbackMessages(); ?>
    <label class="col-4 col-form-label"><?= Text::get('TITLE_SITE_LOGO') ?></label>
    <div class="col-8">
        <form method="post" enctype="multipart/form-data">
            <label for="avatar_file">
                Selecteer een afbeelding om als logo te gebruiken (momenteel ondersteunen we alleen JPEG formaat).
            </label>
            <input type="file" name="logo_file" required />
            <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
            <input type="submit" name="uploadLogo" value="Logo uploaden" />
        </form>
    </div>
</div>

<?= $this->end();
