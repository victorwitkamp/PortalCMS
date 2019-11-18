<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Email\Template\EmailTemplatePDOReader;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_MAIL_TEMPLATES');
Authentication::checkAuthentication();
Authorization::verifyPermission('mail-templates');
require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>

</head>
<body>
<?php require DIR_INCLUDES . 'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?= $pageName ?></h1></div>
                <div class="col-sm-4">
                    <a href="new.php" class="btn btn-info float-right"><span class="fa fa-plus"></span> <?= Text::get('TITLE_NEW_MAIL_TEMPLATE') ?></a>
                </div>
            </div>
            <hr>
            <?php Alert::renderFeedbackMessages(); ?>
            <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th>acties</th>
                        <th>id</th>
                        <th>name</th>
                        <th>type</th>
                        <th>subject</th>
                        <!-- <th>body</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (EmailTemplatePDOReader::get() as $template) {
                        ?><tr>
                            <td>
                                <form method="post">
                                    <a href="edit.php?id=<?= $template['id'] ?>" title="Gegevens wijzigen" class="btn btn-warning btn-sm">
                                        <span class="fa fa-edit"></span>
                                    </a>
                                    <button name="deleteTemplate" type="submit" onclick="return confirm('Weet je zeker dat je de template <?= $template['name'] ?> wilt verwijderen?')" class="btn btn-sm btn-danger">
                                    <i class="far fa-trash-alt"></i>
                                    </button>
                                    <input type="hidden" name="id" value="<?= $template['id'] ?>">
                                </form>
                            </td>
                            <td><?= $template['id'] ?></td>
                            <td><?= $template['name'] ?></td>
                            <td><?= $template['type'] ?></td>
                            <td><?= $template['subject'] ?></td>
                        <tr><?php
                    } ?>
                </tbody>
            </table>

        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
