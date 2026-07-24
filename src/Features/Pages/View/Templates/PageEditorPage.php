<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);


$pageName = 'Pagina ' . $page->name . ' bewerken';
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
            <h3>Pagina "<?= $page->name ?>" bewerken</h3>
        </div>
        <hr>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
        <form method="post" action="/Page/Edit">
            <div class="mb-3 mb-3-sm row">
                <div class="col-sm-12">
                    <textarea id="mytextarea" name="content" cols="50" rows="15"
                              required><?= $page->content ?></textarea>
                </div>
            </div>
            <input type="hidden" name="id" value="<?= $page->id ?>">
            <input type="submit" class="btn btn-sm btn-primary" value="Opslaan">
            <a href="javascript:history.back()" class="btn btn-sm btn-danger">Annuleren</a>
        </form>
    </div>
<?= $this->end();
