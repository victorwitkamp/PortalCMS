<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Users\Repository\RoleRepository;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[ORM\Table(name: 'roles')]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'role_id', type: 'integer')]
    public int $role_id;

    #[ORM\Column(type: 'string', length: 50)]
    public string $role_name;

    /** @var Collection<int, Permission> */
    #[ORM\ManyToMany(targetEntity: Permission::class)]
    #[ORM\JoinTable(name: 'role_perm')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'role_id')]
    #[ORM\InverseJoinColumn(name: 'perm_id', referencedColumnName: 'perm_id')]
    public Collection $permissions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }

    public static function create(string $name): self
    {
        $role = new self();
        $role->role_name = $name;

        return $role;
    }

    public function rename(string $name): void
    {
        $this->role_name = $name;
    }

    public function addPermission(Permission $permission): void
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }
    }

    public function hasPermission(Permission $permission): bool
    {
        return $this->permissions->contains($permission);
    }

    public function removePermission(Permission $permission): void
    {
        $this->permissions->removeElement($permission);
    }
}
