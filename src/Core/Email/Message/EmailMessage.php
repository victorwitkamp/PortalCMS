<?php


declare(strict_types=1);

namespace App\Core\Email\Message;

class EmailMessage
{
    /**
     * Subject
     * @var string
     */
    public string $subject;

    /**
     * Body
     * @var string
     */
    public string $body;

    /**
     * Attachments
     * @var array|null
     */
    public ?array $attachments = [];

    /**
     * Recipients
     * @var array|null
     */
    public ?array $recipients = [];

    public function __construct(string $subject, string $body, array $recipients = null, array $attachments = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->recipients = $recipients;
        $this->attachments = $attachments;
    }
}
