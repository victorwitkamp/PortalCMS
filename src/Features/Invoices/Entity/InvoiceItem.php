<?php

declare(strict_types=1);

namespace PortalCMS\Features\Invoices\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'invoice_items')]
#[ORM\Index(name: 'invoice_id', columns: [ 'invoice_id' ])]
class InvoiceItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\ManyToOne(targetEntity: Invoice::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'invoice_id', referencedColumnName: 'id', nullable: false)]
    public Invoice $invoice;

    #[ORM\Column(name: 'invoice_id', type: 'integer', insertable: false, updatable: false)]
    public int $invoice_id;

    #[ORM\Column(type: 'string', length: 50)]
    public string $name;

    #[ORM\Column(type: 'integer')]
    public int $price;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', nullable: true, insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP')]
    public ?DateTimeImmutable $ModificationDate = null;

    public function __construct(Invoice $invoice, string $name, int $price)
    {
        $this->invoice = $invoice;
        $this->name = $name;
        $this->price = $price;
        $this->CreationDate = new DateTimeImmutable();
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }

    public function changePrice(int $price): void
    {
        $this->price = $price;
    }
}
