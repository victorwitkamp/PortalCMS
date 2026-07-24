<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Email\Entity\MailAttachment;
use PortalCMS\Features\Email\Entity\MailTemplate;

/**
 * @extends EntityRepository<MailTemplate>
 */
final class MailTemplateRepository extends EntityRepository
{
    /**
     * @return MailTemplate[]
     */
    public function findAllOrdered(): array
    {
        return $this->findBy([], [ 'id' => 'ASC' ]);
    }

    /**
     * @return MailTemplate[]
     */
    public function findByType(string $type): array
    {
        return $this->findBy([ 'type' => $type ], [ 'id' => 'ASC' ]);
    }

    public function findSystem(string $name): ?MailTemplate
    {
        return $this->findOneBy([ 'type' => 'system', 'name' => $name ]);
    }

    public function save(MailTemplate $template): void
    {
        $this->getEntityManager()->persist($template);
    }

    public function remove(MailTemplate $template): bool
    {
        if ($template->isSystem()) {
            return false;
        }
        $this->getEntityManager()->remove($template);
        return true;
    }

    public function findAttachment(int $id): ?MailAttachment
    {
        return $this->getEntityManager()->find(MailAttachment::class, $id);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
