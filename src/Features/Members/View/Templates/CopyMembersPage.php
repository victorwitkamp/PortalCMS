<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = 'NewFromExisting';
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

    <!--    <link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">-->
    <!--    <script src="/dist/merged/dataTables.min.js"></script>-->
    <!--    <script src="/includes/js/init.datatables.js" class="init"></script>-->

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?= $pageName ?></h1>
            </div>
        </div>

        <hr>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>

        <ul>
            <?php
            foreach ($years as $year) {
                ?>
                <li>
                <a href="/Membership/NewFromExisting?Year=<?= $year ?><?= (!empty($selectedPaymentType)) ? '&PaymentType=' . $selectedPaymentType : '' ?>"><?= $year ?></a>
                (<?= $yearCounts[$year] ?? 0 ?>)<?php if ($selectedYear === $year) {
                    echo ' - Huidige selectie';
                } ?>
                </li><?php
            } ?>
        </ul>

        <ul>
            <?php
            foreach ($paymentTypes as $paymentType) {
                ?>
                <li>
                <a
                href="/Membership/NewFromExisting?PaymentType=<?= $paymentType ?><?= (!empty($selectedYear)) ? '&Year=' . $selectedYear : '' ?>"><?= $paymentType ?></a><?= ($selectedPaymentType === $paymentType) ? ' - Huidige selectie' : '' ?>
                </li><?php
            } ?>
        </ul>

        <?php
        if (!empty($members)) {
            echo $this->insert('Members::Partials/MemberCopyTable', [ 'members' => $members ]);
        } else {
            echo Text::get('LABEL_NOT_FOUND');
        }
        ?>
    </div>

<?= $this->end();
