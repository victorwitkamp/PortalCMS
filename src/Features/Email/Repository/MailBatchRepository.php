<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PortalCMS\Features\Email\Entity\MailBatch;

/**
 * @extends ServiceEntityRepository<MailBatch>
 */
final class MailBatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailBatch::class);
    }

    /** @return MailBatch[] */
    public function findAllOrdered(): array
    {
        return $this->findBy([], [ 'id' => 'ASC' ]);
    }

    public function countMessages(int $batchId): int
    {
        return (int) $this->getEntityManager()->getConnection()->fetchOne(
            'SELECT COUNT(1) FROM mail_schedule WHERE batch_id = ?',
            [ $batchId ],
        );
    }

    public function save(MailBatch $batch): void
    {
        $this->getEntityManager()->persist($batch);
    }

    public function remove(MailBatch $batch): void
    {
        $this->getEntityManager()->remove($batch);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
