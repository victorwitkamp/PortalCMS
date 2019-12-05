<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Config\Config;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;
use PortalCMS\Core\Email\Recipient\EmailRecipientMapper;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_MAIL_DETAILS');
Authentication::checkAuthentication();
Authorization::verifyPermission('mail-scheduler');
require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
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
    Redirect::to('Error/Error');
}
?>
<?php require DIR_VIEW . 'Parts/Nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?= $pageName ?></h1>
            </div>
            <?php Alert::renderFeedbackMessages(); ?>
        </div>
        <div class="container">
            <div class="row">
            <div class="col-8">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_ID') ?></th><td><?= $row->id ?></td>
                    </tr>

                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_RECIPIENT_TO') ?></th>
                        <td>
                            <?php
                            $EmailRecipientMapper = new EmailRecipientMapper();
                            $recipients = $EmailRecipientMapper->getRecipients($row->id);
                            if (!empty($recipients)) {
                                foreach ($recipients as $recipient) {
                                    if (!empty($recipient['name'])) {
                                        echo $recipient['name'] . ' - ';
                                    }
                                    echo $recipient['email'];
                                    echo '<br>';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_RECIPIENT_CC') ?></th>
                        <td>
                            <?php
                            $ccrecipients = EmailRecipientMapper::getCC($row->id);
                            if (!empty($ccrecipients)) {
                                foreach ($ccrecipients as $ccrecipient) {
                                    if (!empty($ccrecipient['name'])) {
                                        echo $ccrecipient['name'] . ' - ';
                                    }
                                    echo $ccrecipient['email'];
                                    echo '<br>';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_RECIPIENT_BCC') ?></th>
                        <td>
                            <?php
                            $bccrecipients = EmailRecipientMapper::getBCC($row->id);
                            if (!empty($bccrecipients)) {
                                echo 'BCC: <br>';
                                foreach ($bccrecipients as $bccrecipient) {
                                    if (!empty($bccrecipient['name'])) {
                                        echo $bccrecipient['name'] . ' - ';
                                    }
                                    echo $bccrecipient['email'];
                                    echo '<br>';
                                }
                            }

                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_SUBJECT') ?></th><td><?= $row->subject ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_BODY') ?></th><td><?= $row->body ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_ATTACHMENTS') ?></th>
                        <td><?php
                            $attachments = EmailAttachmentMapper::getByMailId($row->id);
                            if (!empty($attachments)) {
                                foreach ($attachments as $attachment) {
                                    $file = $attachment['path'] . $attachment['name'] . $attachment['extension'];

                                    echo '<a href="' . Config::get('URL') . $file . '">' . $file . '</a><br>';
                                }
                            } else {
                                echo 'n/a';
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_MEMBER_ID') ?></th><td><?= $row->member_id ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_USER_ID') ?></th><td><?= $row->user_id ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-4">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_SENDER') ?></th><td><?= $row->sender_email ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_DATE_SENT') ?></th><td><?= $row->DateSent ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_STATUS') ?></th><td><?= $row->status ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_ERROR') ?></th><td><?= $row->errormessage ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_DATE_CREATION') ?></th><td><?= $row->CreationDate ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_DATE_MODIFICATION') ?></th><td><?= $row->ModificationDate ?></td>
                    </tr>
                </table>
            </div>
            </div>
        </div>
    </div>
</main>
<?php require DIR_VIEW . 'Parts/Footer.php'; ?>
</body>
</html>