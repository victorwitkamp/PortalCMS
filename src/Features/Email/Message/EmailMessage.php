<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Features\Email\Message;

use PortalCMS\Features\Email\Entity\MailAttachment;
use PortalCMS\Features\Email\Recipient\EmailRecipient;

final readonly class EmailMessage
{
    /**
     * @param EmailRecipient[] $recipients
     * @param MailAttachment[] $attachments
     */
    public function __construct(
        public string $subject,
        public string $body,
        public array $recipients,
        public array $attachments = [],
    ) {
    }
}
