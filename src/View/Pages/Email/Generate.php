<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Alert;

$pageName = 'Nieuw bericht';
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>


<div class="container">
    <div class="row mt-5">
        <div class="col-sm-12">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
</div>
<hr>
<div class="container">
    <?php
    Alert::renderFeedbackMessages();
    ?>
    <h2>Nieuw bericht met template</h2>
    <p>Aan wie wil je een e-mail versturen?<br>
        <a href="/Email/GenerateMember">Lid</a><br>
        <!-- <a href="user.php">Gebruiker</a> -->
</div>

<?= $this->end()
