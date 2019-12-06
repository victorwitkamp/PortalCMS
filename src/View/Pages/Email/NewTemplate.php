<?php

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_NEW_MAIL_TEMPLATE');
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<script src='https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=y6xawmw19w565wdi90wrtlow2ll6498emv0fozfrtrt7vb4y'></script>
<script>
    tinymce.init({
        selector: '#body',
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
        <div class="col-sm-4">
            <!-- <a href="#" class="btn btn-info float-right"><span class="fa fa-plus"></span> Nieuwe template</a> -->
        </div>
    </div>
    <hr>
    <?php Alert::renderFeedbackMessages(); ?>

    <form method="post">
        <div class="form-group">
            <label for="subject">Onderwerp</label>
            <input type="text" name="subject" class="form-control" id="subject" placeholder="Onderwerp">
        </div>
        <div class="form-group">
            <label for="body">Onderwerp</label>
            <textarea class="form-control" id="body" name="body" cols="50" rows="15"></textarea>
        </div>
        <input type="submit" class="btn btn-primary" name="newTemplate" />
    </form>
    <hr>
    <p>Beschikbare placeholders voor signup: username, sitename, activatelink, activateformlink, confcode</p>
    <p>Beschikbare placeholders voor password reset: USERNAME, RESETLINK, SITENAME</p>
</div>

<?= $this->end();
