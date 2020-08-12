<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Page;

$pageName = 'Pagina bewerken';

Authentication::checkAuthentication();

$row = Page::getPage((int)Request::get('id'));
if (empty($row)) {
    header('Location: /index.php');
    die;
}


$pageName = 'Pagina ' . $row ['name'] . ' bewerken';
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>
    <script src='https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=y6xawmw19w565wdi90wrtlow2ll6498emv0fozfrtrt7vb4y'></script>
    <script>
        import tinymce from "bs-custom-file-input";

        tinymce.init({
            selector: '#mytextarea',
            plugins: 'advlist autolink link image lists charmap print preview'
        });
    </script>
<?= $this->end() ?>
<?= $this->push('main-content') ?>
    <div class="container">
        <div class="row mt-5">
            <h3>Pagina "<?= $row ['name'] ?>" bewerken</h3>
        </div>
        <hr>
        <?php Alert::renderFeedbackMessages(); ?>
    </div>
    <div class="container">
        <form method="post">
            <div class="form-group form-group-sm row">
                <div class="col-sm-12">
                    <textarea id="mytextarea" name="content" cols="50" rows="15"
                              required><?= $row ['content'] ?></textarea>
                </div>
            </div>
            <input type="hidden" name="id" value="<?= $row ['id'] ?>">
            <input type="submit" name="updatePage" class="btn btn-sm btn-primary" value="Opslaan">
            <a href="javascript:history.back()" class="btn btn-sm btn-danger">Annuleren</a>
        </form>
    </div>
<?= $this->end();
