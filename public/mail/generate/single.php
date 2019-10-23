<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\HTTP\Redirect;

$pageName = 'New single message';
require $_SERVER['DOCUMENT_ROOT']. '/Init.php';
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege('mail-scheduler')) {
    Redirect::permissionError();
    die();
}
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>

</head>
<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">

        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-12"><h1><?php echo $pageName ?></h1></div>
            </div>
        </div>
        <hr>
        <div class="container">
            <?php
            Alert::renderFeedbackMessages();
            ?>
            <h2>New single message</h2>
            <form method="post">
                <div class="form-group">
                    <label for="From">From</label>
                    <input type="text" class="form-control" name="From" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="To">To</label>
                    <input type="text" class="form-control" name="To" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="To">CC</label>
                    <input type="text" class="form-control" name="CC" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="To">BCC</label>
                    <input type="text" class="form-control" name="BCC" placeholder="Email">
                </div>
                <input type="submit" name="testeventmail" value="Verzenden">
            </form>
        </div>

    </div>
</main>
</body>
</html>
