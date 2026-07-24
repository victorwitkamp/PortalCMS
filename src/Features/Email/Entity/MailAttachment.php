<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'mail_attachments')]
class MailAttachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    /** Was an unenforced plain int column; added as a real FK in this migration. */
    #[ORM\ManyToOne(targetEntity: MailSchedule::class, inversedBy: 'attachments')]
    #[ORM\JoinColumn(name: 'mail_id', referencedColumnName: 'id', nullable: true)]
    public ?MailSchedule $mail = null;

    #[ORM\Column(name: 'mail_id', type: 'integer', nullable: true, insertable: false, updatable: false)]
    public ?int $mail_id = null;

    /** Was an unenforced plain int column; added as a real FK in this migration. */
    #[ORM\ManyToOne(targetEntity: MailTemplate::class, inversedBy: 'attachments')]
    #[ORM\JoinColumn(name: 'template_id', referencedColumnName: 'id', nullable: true)]
    public ?MailTemplate $template = null;

    #[ORM\Column(name: 'template_id', type: 'integer', nullable: true, insertable: false, updatable: false)]
    public ?int $template_id = null;

    #[ORM\Column(type: 'string', length: 254, nullable: true)]
    public ?string $path = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $extension = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $encoding = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $type = null;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', nullable: true, insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    public ?DateTimeImmutable $ModificationDate = null;

    public function __construct()
    {
        $this->CreationDate = new DateTimeImmutable();
    }

    public function attachToMail(MailSchedule $mail): void
    {
        $this->mail = $mail;
        $this->template = null;
    }

    public function attachToTemplate(MailTemplate $template): void
    {
        $this->template = $template;
        $this->mail = null;
    }

    public function describeFile(string $path, string $name, string $extension, string $encoding, string $type): void
    {
        $this->path = $path;
        $this->name = $name;
        $this->extension = $extension;
        $this->encoding = $encoding;
        $this->type = $type;
    }

    public function copyToMail(MailSchedule $mail): self
    {
        $copy = new self();
        $copy->attachToMail($mail);
        $copy->describeFile(
            (string) $this->path,
            (string) $this->name,
            (string) $this->extension,
            $this->encoding ?? 'base64',
            $this->type ?? 'application/octet-stream',
        );

        return $copy;
    }
}
