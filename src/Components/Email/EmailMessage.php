<?php
namespace PortalCMS\Email;

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
     * Attachment data
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
