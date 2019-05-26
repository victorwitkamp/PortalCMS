<?php

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_RECENT_ACTIVITY');
Auth::checkAuthentication();
if (!Permission::hasPrivilege("recent-activity")) {
    Redirect::permissionerror();
    die();
}
require DIR_ROOT.'includes/functions.php';
require DIR_ROOT.'includes/head.php';
displayHeadCSS();
PortalCMS_JS_headJS();
?>
</head>
<body>
<?php require DIR_ROOT.'includes/nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-12">
                    <h1><?php echo $pageName; ?></h1>
                </div>
            </div>
            <?php Util::DisplayMessage(); View::renderFeedbackMessages(); ?>
        </div>
        <div class="container">
            <?php //require 'content.php'; ?>
            <table class="table table-sm table-striped table-hover table-dark">
                <thead>
                    <th>CreationDate</th>
                    <th>activity_id</th>
                    <th>user_id</th>
                    <th>user_name</th>
                    <th>ip_address</th>

                    <th>activity</th>
</thead>
                <?php $Activities = UserActivity::load();
                foreach ($Activities as $Activity) {
                    ?>
                        <tr>
                            <td><?php echo $Activity['CreationDate']; ?></td>
                            <td><?php echo $Activity['id']; ?></td>
                            <td><?php echo $Activity['user_id']; ?></td>
                            <td><?php echo $Activity['user_name']; ?></td>
                            <td><?php echo $Activity['ip_address']; ?></td>

                            <td><?php echo $Activity['activity']; ?></td>
                            <td><?php echo $Activity['details']; ?></td>

                        </tr>
                    <?php
                }
                ?>
            </table>
        </div>

    </div>
</main>
<?php require DIR_ROOT.'includes/footer.php'; ?>
</body>
</html>