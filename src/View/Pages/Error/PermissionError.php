<?php

$pageName = 'Geen toegang';
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<?= $this->end() ?>
<?= $this->push('main-content') ?>

<div class="container">
    <div class="row mt-5">
        <div class="col-sm-8">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <p>U heeft niet voldoende rechten voor de gekozen actie</p>
    <button onclick="goBack()" class="btn btn-outline-success my-2 my-sm-0"><span class="fa fa-angle-left"></span> Ga terug</button>
</div>

<?= $this->end();
