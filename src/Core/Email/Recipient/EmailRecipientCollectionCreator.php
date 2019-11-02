<?php

namespace PortalCMS\Core\Email\Recipient;

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
            $EmailRecipient = new EmailRecipient($recipient['name'], $recipient['email']);
            $this->recipients[] = $EmailRecipient->get();
        }
        return $this->recipients;
    }
}
