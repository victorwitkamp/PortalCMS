<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Users\Entity\Permission;
use PortalCMS\Features\Users\Entity\Role;

/**
 * @extends EntityRepository<Role>
 */
final class RoleRepository extends EntityRepository
{
    /**
     * @return Role[]
     */
    public function findAllOrdered(): array
    {
        return $this->findBy([], [ 'role_id' => 'ASC' ]);
    }

    /**
     * @return Permission[]
     */
    public function findPermissions(Role $role): array
    {
        $permissions = $role->permissions->toArray();
        usort(
            $permissions,
            static fn (Permission $left, Permission $right): int => $left->perm_id <=> $right->perm_id,
        );

        return $permissions;
    }

    /**
     * @return Permission[]
     */
    public function findSelectablePermissions(Role $role): array
    {
        $assigned = $role->permissions->toArray();
        return array_values(array_filter(
            $this->getEntityManager()->getRepository(Permission::class)->findBy([], [ 'perm_id' => 'ASC' ]),
            static fn (Permission $permission): bool => !in_array($permission, $assigned, true),
        ));
    }

    public function isAssignedToUsers(Role $role): bool
    {
        return (int) $this->getEntityManager()->getConnection()->fetchOne(
            'SELECT COUNT(*) FROM user_role WHERE role_id = ?',
            [ $role->role_id ],
        ) > 0;
    }

    public function save(Role $role): void
    {
        $this->getEntityManager()->persist($role);
    }

    public function remove(Role $role): void
    {
        $this->getEntityManager()->remove($role);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
