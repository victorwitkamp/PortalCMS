<?php

declare(strict_types=1);

namespace PortalCMS\Features\Products\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PortalCMS\Features\Products\Entity\Product;

/**
 * @extends ServiceEntityRepository<Product>
 */
final class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

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
