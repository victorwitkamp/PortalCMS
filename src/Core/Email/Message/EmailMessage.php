<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Message;

/**
 * Class EmailMessage
 * @package PortalCMS\Core\Email\Message
 */
class EmailMessage
{
    /**
     * Subject
     * @var string
     */
    public $subject;

    /**
     * Body
     * @var string
     */
    public $body;

    /**
     * Attachments
     * @var array
     */
    public $attachments = [];

    /**
     * Recipients
     * @var array
     */
    public $recipients = [];

    public function __construct(string $subject, string $body, array $recipients = null, array $attachments = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->recipients = $recipients;
        $this->attachments = $attachments;
    }
}
