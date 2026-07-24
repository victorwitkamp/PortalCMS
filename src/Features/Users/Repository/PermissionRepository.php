<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Users\Entity\Permission;
use PortalCMS\Features\Users\Entity\User;

/**
 * @extends EntityRepository<Permission>
 */
final class PermissionRepository extends EntityRepository
{
    /** @return Permission[] */
    public function findAllOrdered(): array
    {
        return $this->findBy([], [ 'perm_id' => 'ASC' ]);
    }

    /**
     * @return Permission[]
     */
    public function findByUserId(int $userId): array
    {
        $user = $this->getEntityManager()->find(User::class, $userId);
        if (!$user instanceof User) {
            return [];
        }

        $permissions = [];
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[$permission->perm_id] = $permission;
            }
        }
        ksort($permissions);

        return array_values($permissions);
    }
}
