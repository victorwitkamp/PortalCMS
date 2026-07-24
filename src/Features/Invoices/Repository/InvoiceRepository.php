<?php

declare(strict_types=1);

namespace PortalCMS\Features\Invoices\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Invoices\Entity\Invoice;
use PortalCMS\Features\Invoices\Entity\InvoiceItem;

/**
 * @extends EntityRepository<Invoice>
 */
final class InvoiceRepository extends EntityRepository
{
    public function findByNumber(string $number): ?Invoice
    {
        return $this->findOneBy([ 'factuurnummer' => $number ]);
    }

    /**
     * @return Invoice[]
     */
    public function findByContractId(int $contractId): array
    {
        return $this->findBy([ 'contract' => $contractId ], [ 'factuurnummer' => 'ASC' ]);
    }

    /**
     * @return Invoice[]
     */
    public function findByContractIdAndYear(int $contractId, int $year): array
    {
        return $this->findBy(
            [ 'contract' => $contractId, 'year' => $year ],
            [ 'factuurnummer' => 'ASC' ],
        );
    }

    /**
     * @return Invoice[]
     */
    public function findAllOrdered(): array
    {
        return $this->findBy([], [ 'factuurnummer' => 'ASC' ]);
    }

    /**
     * @return Invoice[]
     */
    public function findByYear(int $year): array
    {
        return $this->findBy([ 'year' => $year ], [ 'factuurnummer' => 'ASC' ]);
    }

    /**
     * @return int[]
     */
    public function findYears(): array
    {
        return array_map(
            static fn (mixed $year): int => (int) $year,
            $this->getEntityManager()->getConnection()->fetchFirstColumn(
                'SELECT DISTINCT year FROM invoices ORDER BY year DESC'
            ),
        );
    }

    public function countByYear(int $year): int
    {
        return $this->count([ 'year' => $year ]);
    }

    public function countAll(): int
    {
        return $this->count([]);
    }

    public function findItem(int $itemId): ?InvoiceItem
    {
        return $this->getEntityManager()->find(InvoiceItem::class, $itemId);
    }

    public function save(Invoice $invoice): void
    {
        $this->getEntityManager()->persist($invoice);
    }

    public function remove(Invoice $invoice): void
    {
        $this->getEntityManager()->remove($invoice);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
