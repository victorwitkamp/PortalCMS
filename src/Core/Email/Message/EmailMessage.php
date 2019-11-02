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
     * Attachments
     *
     * @var array
     */
    public $attachments = [];

    /**
     * Recipients
     *
     * @var array
     */
    public $recipients = [];

    public function __construct($subject, $body, $recipients = null, $attachments = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = $attachments;
        $this->recipients = $recipients;
    }
}
