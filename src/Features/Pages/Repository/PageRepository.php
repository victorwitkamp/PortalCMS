<?php

declare(strict_types=1);

namespace PortalCMS\Features\Pages\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PortalCMS\Features\Pages\Entity\Page;

/**
 * @extends ServiceEntityRepository<Page>
 */
final class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
