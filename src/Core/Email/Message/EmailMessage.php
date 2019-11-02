<?php

namespace PortalCMS\Core\Email\Message;

class EmailMessage
{
    /**
     * Subject
     *
     * @var string
     */
    public $subject = null;

    /**
     * Body
     *
     * @var string
     */
    public $body = null;

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
}
