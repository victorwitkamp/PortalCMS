<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Email\Repository\MailScheduleRepository;

#[ORM\Entity(repositoryClass: MailScheduleRepository::class)]
#[ORM\Table(name: 'mail_schedule')]
class MailSchedule
{
    public const STATUS_SCHEDULED = 1;
    public const STATUS_SENT = 2;
    public const STATUS_FAILED = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(name: 'batch_id', type: 'integer', nullable: true)]
    public ?int $batch_id = null;

    #[ORM\Column(type: 'string', length: 254, nullable: true)]
    public ?string $sender_email = null;

    #[ORM\Column(type: 'string', length: 254, nullable: true)]
    public ?string $recipient_email = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $subject = null;

    // columnDefinition pins the existing TEXT on both — Doctrine's plain
    // 'text' type generates LONGTEXT, an unintended widening.
    #[ORM\Column(type: 'text', nullable: true, columnDefinition: 'TEXT DEFAULT NULL')]
    public ?string $body = null;

    #[ORM\Column(type: 'text', nullable: true, columnDefinition: 'TEXT DEFAULT NULL')]
    public ?string $attachment = null;

    #[ORM\Column(name: 'member_id', type: 'integer', nullable: true)]
    public ?int $member_id = null;

    #[ORM\Column(name: 'user_id', type: 'integer', nullable: true)]
    public ?int $user_id = null;

    #[ORM\Column(type: 'integer', options: [ 'default' => self::STATUS_SCHEDULED ])]
    public int $status = self::STATUS_SCHEDULED;

    #[ORM\Column(type: 'text', nullable: true, columnDefinition: 'TEXT DEFAULT NULL')]
    public ?string $errormessage = null;

    #[ORM\Column(name: 'DateSent', type: 'datetime_immutable', nullable: true)]
    public ?DateTimeImmutable $DateSent = null;

    #[ORM\Column(name: 'CreatedBy', type: 'integer', nullable: true)]
    public ?int $CreatedBy = null;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', nullable: true, insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP')]
    public ?DateTimeImmutable $ModificationDate = null;

    /** @var Collection<int, MailRecipient> */
    #[ORM\OneToMany(mappedBy: 'mail', targetEntity: MailRecipient::class, cascade: [ 'persist', 'remove' ], orphanRemoval: true)]
    #[ORM\OrderBy([ 'id' => 'ASC' ])]
    private Collection $recipients;

    /** @var Collection<int, MailAttachment> */
    #[ORM\OneToMany(mappedBy: 'mail', targetEntity: MailAttachment::class, cascade: [ 'persist', 'remove' ], orphanRemoval: true)]
    #[ORM\OrderBy([ 'id' => 'ASC' ])]
    private Collection $attachments;

    public function __construct()
    {
        $this->recipients = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->CreationDate = new DateTimeImmutable();
    }

    public static function create(
        ?int $batchId,
        ?int $memberId,
        string $subject,
        string $body,
        ?int $createdBy = null,
    ): self {
        $mail = new self();
        $mail->batch_id = $batchId;
        $mail->member_id = $memberId;
        $mail->subject = $subject;
        $mail->body = $body;
        $mail->CreatedBy = $createdBy;

        return $mail;
    }

    public function addRecipient(
        string $email,
        ?string $name = null,
        int $type = MailRecipient::TYPE_TO,
    ): MailRecipient {
        $recipient = new MailRecipient($this, $email, $name, $type);
        $this->recipients->add($recipient);

        return $recipient;
    }

    public function addAttachment(
        string $path,
        string $name,
        string $extension,
        string $encoding = 'base64',
        string $type = 'application/octet-stream',
    ): MailAttachment {
        $attachment = new MailAttachment();
        $attachment->attachToMail($this);
        $attachment->describeFile($path, $name, $extension, $encoding, $type);
        $this->attachments->add($attachment);

        return $attachment;
    }

    public function copyAttachment(MailAttachment $attachment): MailAttachment
    {
        $copy = $attachment->copyToMail($this);
        $this->attachments->add($copy);

        return $copy;
    }

    /** @return Collection<int, MailRecipient> */
    public function recipients(): Collection
    {
        return $this->recipients;
    }

    /** @return Collection<int, MailAttachment> */
    public function attachments(): Collection
    {
        return $this->attachments;
    }

    /** @return MailRecipient[] */
    public function recipientsOfType(int $type): array
    {
        return $this->recipients
            ->filter(static fn (MailRecipient $recipient): bool => $recipient->type === $type)
            ->toArray();
    }

    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function markFailed(string $message): void
    {
        $this->status = self::STATUS_FAILED;
        $this->errormessage = $message;
    }

    public function markSent(
        string $senderName,
        string $senderEmail,
        ?DateTimeImmutable $dateSent = null,
    ): void {
        $this->status = self::STATUS_SENT;
        $this->DateSent = $dateSent ?? new DateTimeImmutable();
        $this->sender_email = $senderName . ' (' . $senderEmail . ')';
        $this->errormessage = null;
    }
}
