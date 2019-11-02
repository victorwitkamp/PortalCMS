<?php

namespace PortalCMS\Core\Email\Recipient;

use PortalCMS\Core\Email\Recipient\EmailRecipient;
use PortalCMS\Core\Email\Recipient\EmailRecipientMapper;

class EmailRecipientCollectionCreator
{
    /**
     * Recipients
     *
     * @var array
     */
    public $recipients = [];
    public $mapper = null;

    public function __construct()
    {
        $this->mapper = new EmailRecipientMapper();
    }

    public function createCollection(int $mailId) {
        $emailRecipients = $this->mapper->getRecipients($mailId);
        // var_dump($emailRecipients);
        // die;
        foreach ($emailRecipients as $recipient) {
            // var_dump($recipient);
            // die;
            // $emailRecipient = new EmailRecipient($recipient->name, $recipient->email);
            $this->recipients[] = $recipient;
            // $this->recipients[] = $emailRecipient;
        }
        return $this->recipients;
    }
}
