<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Recipient;

/**
 * Class EmailRecipientCollectionCreator
 * @package PortalCMS\Core\Email\Recipient
 */
class EmailRecipientCollectionCreator
{
    /**
     * Recipients
     * @var array
     */
    public $recipients;
    public $mapper;

    public function __construct()
    {
        $this->mapper = new EmailRecipientMapper();
    }

    /**
     * @param int $mailId
     * @return array|bool
     */
    public function createCollection(int $mailId)
    {
        $emailRecipients = $this->mapper->getAll($mailId);
        if (empty($emailRecipients)) {
            return false;
        }
        foreach ($emailRecipients as $recipient) {
            $EmailRecipient = new EmailRecipient($recipient['name'], $recipient['email']);
            $this->recipients[] = $EmailRecipient->get();
        }
        return $this->recipients;
    }
}
