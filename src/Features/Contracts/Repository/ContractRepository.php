<?php

declare(strict_types=1);

namespace PortalCMS\Features\Contracts\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PortalCMS\Features\Contracts\Entity\Contract;

/**
 * @extends ServiceEntityRepository<Contract>
 */
final class ContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contract::class);
    }

    /**
     * @return Contract[]
     */
    public function findAllOrdered(): array
    {
        return $this->findBy([], [ 'id' => 'ASC' ]);
    }

    public function save(Contract $contract): void
    {
        $this->getEntityManager()->persist($contract);
    }

    public function remove(Contract $contract): void
    {
        $this->getEntityManager()->remove($contract);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
