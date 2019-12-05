<?php

use PortalCMS\Core\View\Alert;

$pageName = 'Fout';

require DIR_ROOT . 'includes/functions.php';
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_JS_headJS();
?>
</head>
<body>
<?php require DIR_VIEW . 'Parts/Nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?= $pageName ?></h1></div>
            </div>
            <?php Alert::renderFeedbackMessages(); ?>
            <button onclick="goBack()" class="btn btn-outline-success my-2 my-sm-0"><span class="fa fa-angle-left"></span> Ga terug</button>
        </div>
    </div>
</main>
<?php require DIR_VIEW . 'Parts/Footer.php'; ?>
<script>
function goBack() {
    window.history.back();
}
</script>
</body>
</html>