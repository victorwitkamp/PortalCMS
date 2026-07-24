<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'mail_recipients')]
class MailRecipient
{
    public const TYPE_TO = 1;
    public const TYPE_CC = 2;
    public const TYPE_BCC = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    public ?string $name = null;

    #[ORM\Column(type: 'string', length: 254)]
    public string $email;

    #[ORM\Column(type: 'integer', options: [ 'default' => 1 ])]
    public int $type = 1;

    #[ORM\ManyToOne(targetEntity: MailSchedule::class, inversedBy: 'recipients')]
    #[ORM\JoinColumn(name: 'mail_id', referencedColumnName: 'id', nullable: false)]
    public MailSchedule $mail;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    public DateTimeImmutable $ModificationDate;

    public function __construct(
        MailSchedule $mail,
        string $email,
        ?string $name = null,
        int $type = self::TYPE_TO,
    ) {
        $this->mail = $mail;
        $this->email = $email;
        $this->name = $name;
        $this->type = $type;
        $this->CreationDate = new DateTimeImmutable();
        $this->ModificationDate = new DateTimeImmutable();
    }

    public function address(string $email, ?string $name = null): void
    {
        $this->email = $email;
        $this->name = $name;
    }
}
