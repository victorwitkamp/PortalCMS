<?php
namespace PortalCMS\Email;

class Message
{
    /**
     * Subject
     *
     * @var string
     */
    private $subject;
    /**
     * Body
     *
     * @var string
     */
    private $body;

    /**
     * Recipients
     *
     * @var array
     */
    private $recipients = [];

    /**
     * Attachment data
     *
     * @var array
     */
    private $attachments = [];

    public function __construct($subject, $body, $recipients, $attachments)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->recipients = $recipients;
        $this->attachments = $attachments;
        // return $this;
    }

}