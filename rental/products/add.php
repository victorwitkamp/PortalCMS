<?php
$pageName = 'Product toevoegen';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Permission::hasPrivilege("rental-products")) {
    Redirect::permissionerror();
    die();
}
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();

PortalCMS_JS_headJS();

PortalCMS_JS_JQuery_Simple_validator(); ?>
</head>
<body>
    <?php require DIR_INCLUDES.'nav.php'; ?>
    <main>
        <div class="content">
            <div class="container">
                <div class="row mt-5">
                    <h1><?php echo $pageName ?></h1>
                </div>

                <?php View::renderFeedbackMessages(); ?>



                <form method="post" validate=true>
                    <div class="form-group row">
                        <div class="col-12">
                            <label class="control-label">name</label>
                            <input type="text" name="name" class="form-control form-control" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <label class="control-label">type</label>
                            <input type="text" name="type" class="form-control form-control" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <label class="control-label">price</label>
                            <input type="text" name="price" class="form-control form-control" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <input type="submit" name="saveNewProduct" class="btn btn-primary" value="Opslaan">
                            <a href="index.php" class="btn btn-danger">Annuleren</a>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </main>
    <?php require DIR_INCLUDES.'footer.php'; ?>
</body>

</html>