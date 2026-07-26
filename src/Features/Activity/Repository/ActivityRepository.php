<?php

declare(strict_types=1);

namespace PortalCMS\Features\Activity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PortalCMS\Features\Activity\Entity\Activity;

/**
 * @extends ServiceEntityRepository<Activity>
 */
final class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

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
