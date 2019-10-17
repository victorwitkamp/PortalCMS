<?php

class Mail
{
    protected $subject;
    protected $body;

    /**
     * Recipients
     *
     * @var array
     */
    protected $recipients = [];

    /**
     * Attachment data
     *
     * @var array
     */
    protected $attachments = [];

    public function __construct($subject, $body, $recipients, $attachments) {
        $this->subject = $subject;
        $this->body = $body;
        $this->recipients = $recipients;
        $this->attachments = $attachments;
        return $this;
    }

}