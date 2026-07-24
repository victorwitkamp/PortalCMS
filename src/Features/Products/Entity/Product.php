<?php

declare(strict_types=1);

namespace PortalCMS\Features\Products\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Products\Repository\ProductRepository;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string', length: 50)]
    public string $name;

    #[ORM\Column(type: 'integer', options: [ 'default' => 1 ])]
    public int $type = 1;

    #[ORM\Column(type: 'integer')]
    public int $price;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', nullable: true, insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP')]
    public ?DateTimeImmutable $ModificationDate = null;

    public function __construct()
    {
        $this->CreationDate = new DateTimeImmutable();
    }
}
