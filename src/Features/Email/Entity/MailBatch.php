<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Email\Repository\MailBatchRepository;

#[ORM\Entity(repositoryClass: MailBatchRepository::class)]
#[ORM\Table(name: 'mail_batches')]
class MailBatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'integer', options: [ 'default' => 0 ], columnDefinition: 'TINYINT(1) NOT NULL DEFAULT 0')]
    public int $status = 0;

    #[ORM\Column(name: 'DateSent', type: 'datetime_immutable', nullable: true)]
    public ?DateTimeImmutable $DateSent = null;

    #[ORM\Column(name: 'UsedTemplate', type: 'integer', nullable: true)]
    public ?int $UsedTemplate = null;

    #[ORM\Column(name: 'CreatedBy', type: 'integer', nullable: true)]
    public ?int $CreatedBy = null;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', nullable: true, insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    public ?DateTimeImmutable $ModificationDate = null;

    public function __construct()
    {
        $this->CreationDate = new DateTimeImmutable();
    }

    public static function create(?int $templateId = null, ?int $createdBy = null): self
    {
        $batch = new self();
        $batch->UsedTemplate = $templateId;
        $batch->CreatedBy = $createdBy;
        $batch->markReady();

        return $batch;
    }

    public function markReady(): void
    {
        $this->status = 1;
    }

    public function markExecuted(?DateTimeImmutable $dateSent = null): void
    {
        $this->status = 2;
        $this->DateSent = $dateSent ?? new DateTimeImmutable();
    }

    public function useTemplate(?int $templateId): void
    {
        $this->UsedTemplate = $templateId;
    }
}
