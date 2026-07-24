<?php

declare(strict_types=1);

namespace PortalCMS\Features\Events\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Events\Repository\EventRepository;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'events')]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(name: 'CreatedBy', type: 'integer')]
    public int $CreatedBy;

    #[ORM\Column(type: 'string', length: 255)]
    public string $title;

    #[ORM\Column(name: 'start_event', type: 'datetime_immutable')]
    public DateTimeImmutable $start_event;

    #[ORM\Column(name: 'end_event', type: 'datetime_immutable')]
    public DateTimeImmutable $end_event;

    // columnDefinition pins the existing TEXT — Doctrine's plain 'text' type
    // generates LONGTEXT, an unintended widening.
    #[ORM\Column(type: 'text', nullable: true, columnDefinition: 'TEXT DEFAULT NULL')]
    public ?string $description = null;

    #[ORM\Column(type: 'integer', nullable: true, options: [ 'default' => 0 ])]
    public ?int $status = 0;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    public DateTimeImmutable $ModificationDate;

    public function __construct()
    {
        $this->CreationDate = new DateTimeImmutable();
        $this->ModificationDate = new DateTimeImmutable();
    }

    public static function create(
        int $createdBy,
        string $title,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        ?string $description,
    ): self {
        $event = new self();
        $event->CreatedBy = $createdBy;
        $event->title = $title;
        $event->start_event = $start;
        $event->end_event = $end;
        $event->description = $description;

        return $event;
    }

    public function update(
        string $title,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        ?string $description,
        int $status,
    ): void {
        $this->rename($title);
        $this->reschedule($start, $end);
        $this->changeDescription($description);
        $this->setStatus($status);
    }

    public function reschedule(DateTimeImmutable $start, DateTimeImmutable $end): void
    {
        $this->start_event = $start;
        $this->end_event = $end;
    }

    public function rename(string $title): void
    {
        $this->title = $title;
    }

    public function changeDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }
}
