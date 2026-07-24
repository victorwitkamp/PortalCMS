<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_DEBUG');
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-12">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
    </div>
    <hr>
    <div class="container">
        <h2>Session context</h2>
        <pre><?= $this->e(print_r($sessionContext, true)) ?></pre>
        <br>
        <h2>sys_get_temp_dir().'/'</h2>
        <p><?= $this->e(sys_get_temp_dir() . '/') ?></p>
    </div>

<?= $this->end();
