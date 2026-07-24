<?php

declare(strict_types=1);

namespace PortalCMS\Features\Pages\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Pages\Entity\Page;

/**
 * @extends EntityRepository<Page>
 */
final class PageRepository extends EntityRepository
{
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
