<?php

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_PRODUCTS');
Auth::checkAuthentication();
if (!Auth::checkPrivilege("rental-products")) {
    Redirect::permissionError();
    die();
}
require_once DIR_INCLUDES.'functions.php';

if (isset($_GET['action']) && !empty($_GET['action'])) {
    if ($_GET['action'] == 'delete') {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            Product::deleteProduct($_GET['id']);
        }
    }
}

require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_CSS_dataTables();
PortalCMS_JS_headJS();
PortalCMS_JS_dataTables();
?>
</head>
<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?php echo $pageName ?></h1></div>
                <div class="col-sm-4"><a href="add.php" class="btn btn-success navbar-btn float-right"><span class="fa fa-plus"></span> Toevoegen</a></div>
            </div>
            <?php Alert::renderFeedbackMessages(); ?><hr>
            <?php
            $products = Product::getAllProducts();
            if ($products) {
                include 'products_table.php';
                PortalCMS_JS_Init_dataTables();
            } else {
                echo 'Ontbrekende gegevens..';
            }
            ?>
        </div>
    </div>
</main>
<?php require DIR_INCLUDES."footer.php"; ?>
</body>