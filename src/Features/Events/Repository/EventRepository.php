<?php

declare(strict_types=1);

namespace PortalCMS\Features\Events\Repository;

use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Events\Entity\Event;

/**
 * @extends EntityRepository<Event>
 */
final class EventRepository extends EntityRepository
{
    /**
     * @return Event[]
     */
    public function findBetween(DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        return $this->createQueryBuilder('event')
            ->where('event.start_event < :end')
            ->andWhere('event.end_event > :start')
            ->orderBy('event.id', 'ASC')
            ->setParameter('end', $end)
            ->setParameter('start', $start)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[]
     */
    public function findUpcoming(DateTimeImmutable $after, int $limit = 3): array
    {
        return $this->createQueryBuilder('event')
            ->where('event.start_event > :after')
            ->orderBy('event.start_event', 'ASC')
            ->setParameter('after', $after)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function save(Event $event): void
    {
        $this->getEntityManager()->persist($event);
    }

    public function remove(Event $event): void
    {
        $this->getEntityManager()->remove($event);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
