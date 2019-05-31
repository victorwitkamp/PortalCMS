<?php

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_MAIL_DETAILS');
Auth::checkAuthentication();
if (!Permission::hasPrivilege("mail-scheduler")) {
    Redirect::permissionerror();
    die();
}
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>
</head>
<body>
<?php
$id = $_GET['id'];

if (MailSchedule::doesMailIdExist($id)) {
    $row = MailSchedule::getScheduledMailById($id);
} else {
    Session::add('feedback_negative', "Geen resultaten voor opgegeven mail ID.");
    Redirect::Error();
}
?>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?php echo $pageName; ?></h1>
            </div>
            <hr>
        </div>
        <div class="container">
            <table class="table table-striped table-condensed">
                <tr>
                    <th width="20%"><?php echo Text::get('LABEL_MAILDETAILS_ID'); ?></th><td><?php echo $row['id']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_SENDER'); ?></th><td><?php echo $row['sender_email']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_RECIPIENT'); ?></th><td><?php echo $row['recipient_email']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_SUBJECT'); ?></th><td><?php echo $row['subject']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_BODY'); ?></th><td><?php echo $row['body']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_MEMBER_ID'); ?></th><td><?php echo $row['member_id']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_USER_ID'); ?></th><td><?php echo $row['user_id']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_STATUS'); ?></th><td><?php echo $row['status']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_ERROR'); ?></th><td><?php echo $row['errormessage']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_DATE_SENT'); ?></th><td><?php echo $row['DateSent']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_DATE_CREATION'); ?></th><td><?php echo $row['CreationDate']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_DATE_MODIFICATION'); ?></th><td><?php echo $row['ModificationDate']; ?></td>
                </tr>
            </table>
        </div>
    </div>
</main>
<?php View::renderFooter(); ?>
</body>
</html>