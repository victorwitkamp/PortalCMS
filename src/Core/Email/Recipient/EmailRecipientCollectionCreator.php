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
    public $recipients;
    public $mapper;

    public function __construct()
    {
        $this->mapper = new EmailRecipientMapper();
    }

    public function createCollection(int $mailId) {
        $emailRecipients = $this->mapper->getAll($mailId);
        foreach ($emailRecipients as $recipient) {
            $EmailRecipient = new EmailRecipient();
            $EmailRecipient->email = $recipient['email'];
            $EmailRecipient->name = $recipient['name'];
            $this->recipients[] = $EmailRecipient->get();
        }
        return $this->recipients;
    }
}
