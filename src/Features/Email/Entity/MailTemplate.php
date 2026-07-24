<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Email\Repository\MailTemplateRepository;

#[ORM\Entity(repositoryClass: MailTemplateRepository::class)]
#[ORM\Table(name: 'mail_templates')]
class MailTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    public ?string $type = null;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    public ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $subject = null;

    // columnDefinition pins the existing TEXT — Doctrine's plain 'text' type
    // generates LONGTEXT, an unintended widening.
    #[ORM\Column(type: 'text', nullable: true, columnDefinition: 'TEXT DEFAULT NULL')]
    public ?string $body = null;

    #[ORM\Column(type: 'integer', options: [ 'default' => 1 ])]
    public int $status = 1;

    #[ORM\Column(name: 'CreatedBy', type: 'integer', nullable: true)]
    public ?int $CreatedBy = null;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    public DateTimeImmutable $ModificationDate;

    /** @var Collection<int, MailAttachment> */
    #[ORM\OneToMany(mappedBy: 'template', targetEntity: MailAttachment::class, cascade: [ 'persist', 'remove' ], orphanRemoval: true)]
    #[ORM\OrderBy([ 'id' => 'ASC' ])]
    private Collection $attachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->CreationDate = new DateTimeImmutable();
        $this->ModificationDate = new DateTimeImmutable();
    }

    public static function create(
        string $type,
        string $subject,
        string $body,
        ?int $createdBy = null,
    ): self {
        $template = new self();
        $template->type = $type;
        $template->subject = $subject;
        $template->body = $body;
        $template->CreatedBy = $createdBy;

        return $template;
    }

    public function rename(?string $name): void
    {
        $this->name = $name;
    }

    public function changeSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function changeBody(?string $body): void
    {
        $this->body = $body;
    }

    public function changeType(?string $type): void
    {
        $this->type = $type;
    }

    public function markSystemTemplate(?string $systemName): void
    {
        $this->name = $systemName;
    }

    public function addAttachment(
        string $path,
        string $name,
        string $extension,
        string $encoding = 'base64',
        string $type = 'application/octet-stream',
    ): MailAttachment {
        $attachment = new MailAttachment();
        $attachment->attachToTemplate($this);
        $attachment->describeFile($path, $name, $extension, $encoding, $type);
        $this->attachments->add($attachment);

        return $attachment;
    }

    public function removeAttachment(MailAttachment $attachment): void
    {
        $this->attachments->removeElement($attachment);
    }

    /** @return Collection<int, MailAttachment> */
    public function attachments(): Collection
    {
        return $this->attachments;
    }

    public function isSystem(): bool
    {
        return $this->type === 'system';
    }
}
