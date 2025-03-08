<?php


declare(strict_types=1);

use App\Core\Config\Config;
use App\Core\Email\Message\Attachment\EmailAttachmentMapper;
use App\Core\Email\Recipient\EmailRecipientMapper;
use App\Core\Email\Schedule\MailScheduleMapper;
use App\Core\HTTP\Request;
use App\Core\Controller\AlertController;
use App\Core\View\Text;

$pageName = Text::get('TITLE_MAIL_DETAILS');
?>
<?php
$id = (int) $this->request->get('id');

if (MailScheduleMapper::exists($id)) {
    $row = MailScheduleMapper::getById($id);
} else {
    return $this->redirectToRoute('errornotfound');
}
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5"><h1><?= $pageName ?></h1></div>
        <?php $Alert = new AlertController();$Alert->renderFeedbackMessages(); ?>
    </div>
    <div class="container">
    <div class="row">

        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?= $row->subject ?></h4>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_SENDER') . ': ' . $row->sender_email ?>
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_RECIPIENT_TO') ?>: <?php
                    $recipientmapper = new EmailRecipientMapper();
                    $recipients = $recipientmapper->getRecipients($row->id);
                    if ($recipients !== null) {
                        foreach ($recipients as $recipient) {
                            // if (!empty($recipient['name'])) { echo $recipient['name'] . ' - '; }
                            echo $recipient['email'] . ', ';
                            // echo '<br>';
                        }
                    }
                    ?>
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_MEMBER_ID') ?>: <?= $row->member_id ?>
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_USER_ID') ?>: <?= $row->user_id ?>
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_RECIPIENT_CC') ?>: <?php
                    $ccrecipients = EmailRecipientMapper::getCC($row->id);
                    if (!empty($ccrecipients)) {
                        foreach ($ccrecipients as $ccrecipient) {
                            if (!empty($ccrecipient['name'])) {
                                echo $ccrecipient['name'] . ' - ';
                            }
                            echo $ccrecipient['email'];
                            echo '<br>';
                        }
                    } ?>

                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_RECIPIENT_BCC') ?>: <?php
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
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_ATTACHMENTS') ?>: <?php
                    $attachments = EmailAttachmentMapper::getByMailId($row->id);
                    if (!empty($attachments)) {
                        foreach ($attachments as $attachment) {
                            $shortfile = $attachment['name'] . $attachment['extension'];
                            $longfile = Config::get('URL') . $attachment['path'] . $shortfile; ?><a href="<?= $longfile ?>"><?= $shortfile ?></a><br><?php
                        }
                    } else {
                        echo 'n/a';
                    }
                    ?>
                </h6>

                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_DATE_SENT') ?>: <?= $row->DateSent ?>
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_STATUS') ?>: <?= $row->status ?>
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_ERROR') ?>: <?= $row->errormessage ?>
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= Text::get('LABEL_MAILDETAILS_DATE_CREATION') ?>: <?= $row->CreationDate ?>
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?php echo Text::get('LABEL_MAILDETAILS_DATE_MODIFICATION') ?>: <?php echo $row->ModificationDate ?>
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">

                </h6>
            </div>
            <div class="card-body">
                <p class="card-text"><?= $row->body ?></p>
            </div>
            <div class="card-body text-muted">
            </div>
        </div>
    </div>

<?= $this->end();
