<?php

declare(strict_types=1);

namespace PortalCMS\Features\Products\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Products\Entity\Product;

/**
 * @extends EntityRepository<Product>
 */
final class ProductRepository extends EntityRepository
{
    /** @return Product[] */
    public function findAllOrdered(): array
    {
        return $this->findBy([], [ 'id' => 'ASC' ]);
    }

    public function save(Product $product): void
    {
        $this->getEntityManager()->persist($product);
    }

    public function remove(Product $product): void
    {
        $this->getEntityManager()->remove($product);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
