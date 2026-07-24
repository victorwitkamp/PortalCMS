<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_SITE_LOGO');
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>


    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-12">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <hr>

        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
        <div class="row">
            <form method="post" action="/Settings/Logo" enctype="multipart/form-data">
                <p>
                    Selecteer een afbeelding om als logo te gebruiken (alleen JPEG formaat).
                </p>
                <div class="mb-3">
                    <label class="form-label" for="customFile">Bestand selecteren</label>
                    <input type="file" class="form-control" id="customFile" name="logo_file" required/>
                </div>
                <hr>
                <input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
                <input type="submit" value="Logo uploaden"/>
            </form>
        </div>
    </div>
<?= $this->end();
