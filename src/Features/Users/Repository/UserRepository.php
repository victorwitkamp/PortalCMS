<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Users\Entity\Role;
use PortalCMS\Features\Users\Entity\User;

/**
 * @extends EntityRepository<User>
 */
final class UserRepository extends EntityRepository
{
    public function usernameExists(string $username, ?int $exceptUserId = null): bool
    {
        $existing = $this->findOneBy([ 'user_name' => $username ]);
        return $existing instanceof User && $existing->user_id !== $exceptUserId;
    }

    public function emailExists(string $email, ?int $exceptUserId = null): bool
    {
        $existing = $this->findOneBy([ 'user_email' => $email ]);
        return $existing instanceof User && $existing->user_id !== $exceptUserId;
    }

    public function findByLogin(string $login): ?User
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.user_name = :login OR user.user_email = :login')
            ->setParameter('login', $login)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByRememberToken(int $userId, string $token): ?User
    {
        return $this->findOneBy([
            'user_id' => $userId,
            'user_remember_me_token' => $token,
        ]);
    }

    public function findByFacebookId(string $facebookId): ?User
    {
        return $this->findOneBy([ 'user_fbid' => $facebookId ]);
    }

    public function findByUsernameOrEmail(string $value): ?User
    {
        return $this->findByLogin($value);
    }

    public function findByResetToken(string $username, string $token): ?User
    {
        return $this->findOneBy([
            'user_name' => $username,
            'password_reset_hash' => $token,
        ]);
    }

    /** @return User[] */
    public function findAllOrdered(): array
    {
        return $this->findBy([], [ 'user_id' => 'ASC' ]);
    }

    /** @return Role[] */
    public function findRoles(int $userId): array
    {
        $user = $this->find($userId);
        if (!$user instanceof User) {
            return [];
        }

        $roles = $user->roles->toArray();
        usort($roles, static fn (Role $left, Role $right): int => $left->role_id <=> $right->role_id);

        return $roles;
    }

    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function remove(User $user): void
    {
        $this->getEntityManager()->remove($user);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
