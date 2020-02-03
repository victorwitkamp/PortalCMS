<?php
declare(strict_types=1);

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_SITE_LOGO');
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<script src="/dist/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>


<?= $this->end() ?>
<?= $this->push('main-content') ?>


<div class="container">
    <div class="row mt-5">
        <div class="col-sm-12">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <hr>

    <?php Alert::renderFeedbackMessages(); ?>
    <div class="row">
        <form method="post" enctype="multipart/form-data">
            <p>
                Selecteer een afbeelding om als logo te gebruiken (alleen JPEG formaat).
            </p>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFile" name="logo_file" required />
                <label class="custom-file-label" for="customFile">Bestand selecteren</label>
            </div>
            <hr>
            <!-- <input type="file" name="logo_file" required /> -->
            <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
            <input type="submit" name="uploadLogo" value="Logo uploaden" />
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        bsCustomFileInput.init()
    })
</script>
<?= $this->end();
