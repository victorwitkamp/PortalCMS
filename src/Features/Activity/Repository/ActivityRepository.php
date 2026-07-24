<?php

declare(strict_types=1);

namespace PortalCMS\Features\Activity\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Activity\Entity\Activity;

/**
 * @extends EntityRepository<Activity>
 */
final class ActivityRepository extends EntityRepository
{
    /**
     * @return Activity[]
     */
    public function findRecent(int $limit = 50): array
    {
        return $this->findBy([], [ 'id' => 'DESC' ], $limit);
    }

    public function save(Activity $activity): void
    {
        $this->getEntityManager()->persist($activity);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
