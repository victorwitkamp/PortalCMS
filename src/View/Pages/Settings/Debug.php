<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_DEBUG');
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-12">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <?php Alert::renderFeedbackMessages(); ?>
    </div>
    <hr>
    <div class="container">
        <h2>var_dump($_SESSION)</h2>
        <?php var_dump($_SESSION); ?>
        <br>
        <h2>print_r($_SESSION)</h2>
        <?php print_r($_SESSION); ?>
        <br>
        <h2>sys_get_temp_dir().'/'</h2>
        <p><?= sys_get_temp_dir() . '/' ?></p>
    </div>

<?= $this->end()
