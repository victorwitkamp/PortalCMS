<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Email\Entity\MailSchedule;

/**
 * @extends EntityRepository<MailSchedule>
 */
final class MailScheduleRepository extends EntityRepository
{
    /** @return MailSchedule[] */
    public function findAllOrdered(): array
    {
        return $this->findBy([], [ 'id' => 'ASC' ]);
    }

    /** @return MailSchedule[] */
    public function findHistory(): array
    {
        return $this->createQueryBuilder('mail')
            ->andWhere('mail.status > :scheduled')
            ->setParameter('scheduled', MailSchedule::STATUS_SCHEDULED)
            ->orderBy('mail.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /** @return MailSchedule[] */
    public function findByBatchId(int $batchId): array
    {
        return $this->findBy([ 'batch_id' => $batchId ], [ 'id' => 'ASC' ]);
    }

    /** @return MailSchedule[] */
    public function findScheduledByBatchId(int $batchId): array
    {
        return $this->findBy(
            [
                'batch_id' => $batchId,
                'status' => MailSchedule::STATUS_SCHEDULED,
            ],
            [ 'id' => 'ASC' ],
        );
    }

    public function save(MailSchedule $mail): void
    {
        $this->getEntityManager()->persist($mail);
    }

    public function remove(MailSchedule $mail): void
    {
        $this->getEntityManager()->remove($mail);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
