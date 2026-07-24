<?php

declare(strict_types=1);

namespace PortalCMS\Features\Invoices\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Contracts\Entity\Contract;
use PortalCMS\Features\Invoices\Repository\InvoiceRepository;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\Table(name: 'invoices')]
// Pins the existing index name — without this, Doctrine's schema tool
// generates its own hashed name and proposes an unnecessary RENAME INDEX.
#[ORM\Index(name: 'contract_id', columns: [ 'contract_id' ])]
class Invoice
{
    public const STATUS_DRAFT = 0;
    public const STATUS_PDF_WRITTEN = 1;
    public const STATUS_MAILED = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\ManyToOne(targetEntity: Contract::class)]
    #[ORM\JoinColumn(name: 'contract_id', referencedColumnName: 'id', nullable: true)]
    public ?Contract $contract = null;

    /** Mirrors `contract` above as a plain scalar for existing call sites reading `->contract_id` directly. */
    #[ORM\Column(name: 'contract_id', type: 'integer', nullable: true, insertable: false, updatable: false)]
    public ?int $contract_id = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $year = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $month = null;

    #[ORM\Column(type: 'string', length: 8, nullable: true)]
    public ?string $factuurnummer = null;

    // Doctrine's non-immutable DateType strictly rejects DateTimeImmutable at
    // write time (verified: throws InvalidType) even though both implement
    // DateTimeInterface — use the _immutable DBAL type instead, which maps to
    // the identical underlying SQL DATE column, just hydrates/accepts
    // DateTimeImmutable (see Contract.php for the same note).
    #[ORM\Column(type: 'date_immutable')]
    public DateTimeImmutable $factuurdatum;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    public ?DateTimeImmutable $vervaldatum = null;

    #[ORM\Column(type: 'integer', nullable: true, options: [ 'default' => 0 ])]
    public ?int $status = 0;

    #[ORM\Column(name: 'mail_id', type: 'integer', nullable: true)]
    public ?int $mail_id = null;

    /** @var Collection<int, InvoiceItem> */
    #[ORM\OneToMany(
        mappedBy: 'invoice',
        targetEntity: InvoiceItem::class,
        cascade: [ 'persist', 'remove' ],
        orphanRemoval: true,
    )]
    #[ORM\OrderBy([ 'id' => 'ASC' ])]
    private Collection $items;

    // insertable/updatable: false — MySQL's own DEFAULT CURRENT_TIMESTAMP
    // manages this entirely (the original raw-SQL mapper never included it
    // in its INSERT either); a placeholder default just keeps the typed
    // property initialized. columnDefinition pins the exact DDL so
    // Doctrine's schema tool doesn't try to convert it to DATETIME.
    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', nullable: true, insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP')]
    public ?DateTimeImmutable $ModificationDate = null;

    public function __construct()
    {
        $this->CreationDate = new DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    public function addItem(string $name, int $price): InvoiceItem
    {
        $item = new InvoiceItem($this, $name, $price);
        $this->items->add($item);

        return $item;
    }

    public function removeItem(InvoiceItem $item): void
    {
        $this->items->removeElement($item);
    }

    /**
     * @return Collection<int, InvoiceItem>
     */
    public function items(): Collection
    {
        return $this->items;
    }

    public function total(): int
    {
        return array_sum($this->items->map(
            static fn (InvoiceItem $item): int => $item->price
        )->toArray());
    }

    public function markPdfWritten(): void
    {
        $this->status = self::STATUS_PDF_WRITTEN;
    }

    public function markMailed(int $mailId): void
    {
        $this->mail_id = $mailId;
        $this->status = self::STATUS_MAILED;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function hasPdf(): bool
    {
        return $this->status !== null && $this->status >= self::STATUS_PDF_WRITTEN;
    }

    public function isMailed(): bool
    {
        return $this->status === self::STATUS_MAILED;
    }

}
