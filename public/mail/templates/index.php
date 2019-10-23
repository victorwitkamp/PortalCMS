<?php

use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Email\Template\MailTemplateMapper;

require $_SERVER['DOCUMENT_ROOT']. '/Init.php';
$pageName = Text::get('TITLE_MAIL_TEMPLATES');
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege('mail-templates')) {
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
                <div class="col-sm-8"><h1><?php echo $pageName ?></h1></div>
                <div class="col-sm-4">
                    <a href="new.php" class="btn btn-info float-right"><span class="fa fa-plus"></span> Nieuwe template</a>
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
                    foreach (MailTemplateMapper::getTemplates() as $row) {
                        echo '<tr>';
                        echo '<td><a href="edit.php?id='.$row['id'].'" title="Gegevens wijzigen" class="btn btn-warning btn-sm">
            <span class="fa fa-edit"></span></a></td>';
                        echo '<td>'.$row['id'].'</td>';
                        echo '<td>'.$row['name'].'</td>';
                        echo '<td>'.$row['type'].'</td>';
                        echo '<td>'.$row['subject'].'</td>';
                        // echo '<td>'.$row['body'].'</td><tr>';
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</main>
<?php include DIR_INCLUDES.'footer.php'; ?>
</body>
</html>
