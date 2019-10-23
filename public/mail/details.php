<?php

use PortalCMS\Core\View\Text;
use PortalCMS\Core\Config\Config;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\Email\Recipient\MailRecipientMapper;
use PortalCMS\Core\Email\Attachment\MailAttachmentMapper;

require $_SERVER['DOCUMENT_ROOT']. '/Init.php';
$pageName = Text::get('TITLE_MAIL_DETAILS');
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
<?php
$id = $_GET['id'];

if (MailScheduleMapper::exists($id)) {
    $row = MailScheduleMapper::getById($id);
} else {
    Session::add('feedback_negative', 'Geen resultaten voor opgegeven mail ID.');
    Redirect::error();
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
                    <th><?php echo Text::get('LABEL_MAILDETAILS_RECIPIENT_TO'); ?></th>
                    <td>
                        <?php
                        $recipients = MailRecipientMapper::getByMailIdAndType($row['id'], 1);
                        if (!empty($recipients)) {
                            foreach ($recipients as $recipient) {
                                if (!empty($recipient['name'])) {
                                    echo $recipient['name'].' - ';
                                }
                                echo $recipient['email'];
                                echo '<br>';
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_RECIPIENT_CC'); ?></th>
                    <td>
                        <?php
                        $ccrecipients = MailRecipientMapper::getByMailIdAndType($row['id'], 2);
                        if (!empty($ccrecipients)) {
                            foreach ($ccrecipients as $ccrecipient) {
                                if (!empty($ccrecipient['name'])) {
                                    echo $ccrecipient['name'].' - ';
                                }
                                echo $ccrecipient['email'];
                                echo '<br>';
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_RECIPIENT_BCC'); ?></th>
                    <td>
                        <?php
                        $bccrecipients = MailRecipientMapper::getByMailIdAndType($row['id'], 3);
                        if (!empty($bccrecipients)) {
                            echo 'BCC: <br>';
                            foreach ($bccrecipients as $bccrecipient) {
                                if (!empty($bccrecipient['name'])) {
                                    echo $bccrecipient['name'].' - ';
                                }
                                echo $bccrecipient['email'];
                                echo '<br>';
                            }
                        }

                        ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_SUBJECT'); ?></th><td><?php echo $row['subject']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_BODY'); ?></th><td><?php echo $row['body']; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::get('LABEL_MAILDETAILS_ATTACHMENT'); ?></th>
                    <td><?php
                    $attachments = MailAttachmentMapper::getByMailId($row['id']);
                    if (!empty($attachments)) {
                        foreach ($attachments as $attachment) {
                            $file = $attachment['path'].$attachment['name'].$attachment['extension'];

                            echo '<a href="'.Config::get('URL').$file.'">'.$file.'</a><br>';
                        }
                    } else {
                        echo 'n/a';
                    }
                    ?></td>
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
<?php include DIR_INCLUDES.'footer.php'; ?>
</body>
</html>
