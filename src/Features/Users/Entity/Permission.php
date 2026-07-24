<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Entity;

use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Users\Repository\PermissionRepository;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
#[ORM\Table(name: 'permissions')]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'perm_id', type: 'integer')]
    public int $perm_id;

    #[ORM\Column(type: 'string', length: 50)]
    public string $perm_desc;

    public function rename(string $description): void
    {
        $this->perm_desc = $description;
    }
}
