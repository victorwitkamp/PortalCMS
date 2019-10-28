<?php

namespace PortalCMS\Core\Email\Message;

class EmailMessage
{
    /**
     * Subject
     *
     * @var string
     */
    public $subject;

    /**
     * Body
     *
     * @var string
     */
    public $body;

    /**
     * Recipients
     *
     * @var array
     */
    public $recipients = [];

    /**
     * Attachments
     *
     * @var array
     */
    public $attachments = [];

    public function __construct($subject, $body, $recipients, $attachments = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->recipients = $recipients;
        $this->attachments = $attachments;
    }
}
