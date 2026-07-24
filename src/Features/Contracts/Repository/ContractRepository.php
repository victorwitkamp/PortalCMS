<?php

declare(strict_types=1);

namespace PortalCMS\Features\Contracts\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Contracts\Entity\Contract;

/**
 * @extends EntityRepository<Contract>
 */
final class ContractRepository extends EntityRepository
{
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
