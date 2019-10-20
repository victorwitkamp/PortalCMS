<?php

use PortalCMS\Authentication\Authentication;
use PortalCMS\Core\Alert;
use PortalCMS\Core\View;
use PortalCMS\Models\Page;

$pageName = 'Pagina bewerken';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Authentication::checkAuthentication();
require_once DIR_INCLUDES.'functions.php';
if (!Page::checkPage($_GET['id'])) {
    header("Location: /index.php");
    die;
} else {
    $row = Page::getPage($_GET['id']);
}

$pageName = 'Pagina '.$row ['name'].' bewerken';

require_once DIR_INCLUDES.'head.php';
displayHeadCSS(); ?>

<?php PortalCMS_JS_headJS(); ?>
<script src='https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=y6xawmw19w565wdi90wrtlow2ll6498emv0fozfrtrt7vb4y'></script>
<script>
tinymce.init({
selector: '#mytextarea',
plugins : 'advlist autolink link image lists charmap print preview'
});
</script>
</head>
<body>
    <?php require DIR_INCLUDES.'nav.php'; ?>
    <main>
        <div class="content">
            <div class="container">
                <div class="row mt-5">
                    <h3>Pagina "<?php echo $row ['name']; ?>" bewerken</h3>
                </div>
                <hr>
                <?php Alert::renderFeedbackMessages(); ?>
                <form method="post" validate=true>
                    <div class="form-group form-group-sm row">
                        <div class="col-sm-12">
                            <textarea id="mytextarea" name="content" cols="50" rows="15" required><?php echo $row ['content']; ?></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $row ['id']; ?>">
                    <input type="submit" name="updatePage" class="btn btn-sm btn-primary" value="Opslaan">
                    <a href="javascript:history.back()" class="btn btn-sm btn-danger">Annuleren</a>
                </form>
            </div>
        </div>
    </main>
    <?php View::renderFooter(); ?>
</body>
</html>
