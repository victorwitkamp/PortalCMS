<?php

declare(strict_types=1);

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\View\Text;
use PortalCMS\Features\Email\Entity\MailRecipient;

$pageName = Text::get('TITLE_MAIL_DETAILS');
$renderRecipients = static function (array $recipients): void {
    foreach ($recipients as $recipient) {
        if (!empty($recipient->name)) {
            echo $recipient->name . ' - ';
        }
        echo $recipient->email . '<br>';
    }
};
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_ID') ?></th>
                        <td><?= $mail->id ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_RECIPIENT_TO') ?></th>
                        <td><?php $renderRecipients($mail->recipientsOfType(MailRecipient::TYPE_TO)); ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_RECIPIENT_CC') ?></th>
                        <td><?php $renderRecipients($mail->recipientsOfType(MailRecipient::TYPE_CC)); ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_RECIPIENT_BCC') ?></th>
                        <td><?php $renderRecipients($mail->recipientsOfType(MailRecipient::TYPE_BCC)); ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_SUBJECT') ?></th>
                        <td><?= $mail->subject ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_BODY') ?></th>
                        <td><?= $mail->body ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_ATTACHMENTS') ?></th>
                        <td>
                            <?php if ($mail->attachments()->isEmpty()) { ?>
                                n/a
                            <?php } else { ?>
                                <?php foreach ($mail->attachments() as $attachment) {
                                    $extension = (string) $attachment->extension;
                                    if ($extension !== '' && !str_starts_with($extension, '.')) {
                                        $extension = '.' . $extension;
                                    }
                                    $file = $attachment->path . $attachment->name . $extension;
                                    ?>
                                    <a href="<?= Config::get('URL') . $file ?>"><?= $file ?></a><br>
                                <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_MEMBER_ID') ?></th>
                        <td><?= $mail->member_id ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_USER_ID') ?></th>
                        <td><?= $mail->user_id ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-4">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_SENDER') ?></th>
                        <td><?= $mail->sender_email ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_DATE_SENT') ?></th>
                        <td><?= $mail->DateSent?->format('Y-m-d H:i:s') ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_STATUS') ?></th>
                        <td><?= $mail->status ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_ERROR') ?></th>
                        <td><?= $mail->errormessage ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_DATE_CREATION') ?></th>
                        <td><?= $mail->CreationDate->format('Y-m-d H:i:s') ?></td>
                    </tr>
                    <tr>
                        <th><?= Text::get('LABEL_MAILDETAILS_DATE_MODIFICATION') ?></th>
                        <td><?= $mail->ModificationDate?->format('Y-m-d H:i:s') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

<?= $this->end();
