<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Contracts\ContractMapper;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_CONTRACTS');
Authentication::checkAuthentication();
Authorization::verifyPermission('rental-contracts');
$contracts = ContractMapper::get();
require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_CSS_dataTables();
PortalCMS_JS_headJS();
PortalCMS_JS_dataTables();
?>

</head>
<body>
<?php require DIR_INCLUDES . 'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?= $pageName ?></h1></div>
                <div class="col-sm-4"><a href="new.php" class="btn btn-success navbar-btn float-right"><span class="fa fa-plus"></span> <?= Text::get('LABEL_ADD') ?></a></div>
            </div>
            <hr>
            <?php
            Alert::renderFeedbackMessages();
            if (!$contracts) {
                echo 'Ontbrekende gegevens..';
            } else { ?>
                <table id="example" class="table table-sm table-striped table-hover" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Huurder</th>
                            <th>Bandcode</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($contracts as $contract) { ?>
                            <tr>
                                <td><a href="view.php?id=<?= $contract->id ?>"><?= $contract->band_naam ?></a></td>
                                <td><?= $contract->bandcode ?></td>
                            </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            <script class="init">
                $(document).ready(function() {
                    var table = $('#example').DataTable({
                        "scrollX": true,
                        "language": {
                            "url": '//cdn.datatables.net/plug-ins/1.10.19/i18n/Dutch.json'
                        }
                    });
                } );
            </script>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
