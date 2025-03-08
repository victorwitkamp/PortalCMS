<?php



declare(strict_types=1);

use App\Core\HTTP\Request;
use App\Core\Controller\AlertController;
use App\Core\View\Text;
use App\Modules\Members\MemberMapper;

$pageName = 'NewFromExisting';
$selectedYear = (int)$this->request->get('Year');
if (!isset($selectedYear) || empty($selectedYear)) {
    $selectedYear = (int)date('Y');
}
$selectedPaymentType = (string)$this->request->get('PaymentType');
if (!isset($selectedPaymentType) || empty($selectedPaymentType)) {
    $selectedPaymentType = 'incasso';
}

?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
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
        <?php $Alert = new AlertController();$Alert->renderFeedbackMessages(); ?>

        <ul>
            <?php
            $years = MemberMapper::getYears();
            foreach ($years as $year) {
                ?>
                <li>
                <a href="/Membership/NewFromExisting?Year=<?= $year ?><?= (!empty($selectedPaymentType)) ? '&PaymentType=' . $selectedPaymentType : '' ?>"><?= $year ?></a>
                (<?= MemberMapper::getMemberCountByYear($year) ?>)<?php if ($selectedYear === $year) {
                    echo ' - Huidige selectie';
                } ?>
                </li><?php
            } ?>
        </ul>

        <ul>
            <?php
            $paymentTypes = MemberMapper::getPaymentTypes();
            foreach ($paymentTypes as $paymentType) {
                ?>
                <li>
                <a
                href="/Membership/NewFromExisting?PaymentType=<?= $paymentType ?><?= (!empty($selectedYear)) ? '&Year=' . $selectedYear : '' ?>"><?= $paymentType ?></a><?= ($selectedPaymentType === $paymentType) ? ' - Huidige selectie' : '' ?>
                </li><?php
            } ?>
        </ul>

        <?php
        if (!empty($selectedYear)) {
            if (isset($selectedPaymentType) && !empty($selectedPaymentType)) {
                $members = MemberMapper::getMembers($selectedYear, $selectedPaymentType);
            } else {
                $members = MemberMapper::getMembers($selectedYear);
            }
        } elseif (!empty($selectedPaymentType)) {
            $members = MemberMapper::getMembers(null, $selectedPaymentType);
        } else {
            $members = MemberMapper::getMembers();
        }
        if (!empty($members)) {
            include_once DIR_VIEW . 'Pages/Membership/inc/table_NewFromExisting.php';
        } else {
            echo Text::get('LABEL_NOT_FOUND');
        }
        ?>
    </div>

<?= $this->end();
