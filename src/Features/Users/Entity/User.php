<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Users\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
// Explicit names, matching the existing constraints exactly — the bare
// unique:true shorthand generates Doctrine's own hashed name and proposes
// an unnecessary RENAME INDEX for both.
#[ORM\UniqueConstraint(name: 'user_name', columns: [ 'user_name' ])]
#[ORM\UniqueConstraint(name: 'user_email', columns: [ 'user_email' ])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'user_id', type: 'integer')]
    public int $user_id;

    #[ORM\Column(type: 'string', length: 64)]
    public string $user_name;

    #[ORM\Column(type: 'string', length: 48, nullable: true)]
    public ?string $session_id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $user_password_hash = null;

    #[ORM\Column(type: 'string', length: 254)]
    public string $user_email;

    #[ORM\Column(type: 'boolean', options: [ 'default' => false ])]
    public bool $user_active = false;

    #[ORM\Column(type: 'boolean', options: [ 'default' => false ])]
    public bool $user_deleted = false;

    // Doctrine's 'smallint' type generates a SMALLINT column, which would
    // widen this away from the live schema's tinyint(1) (this holds small
    // multi-value ints like 7, not just 0/1, so 'boolean' would be wrong
    // too) — columnDefinition pins the exact existing DDL.
    #[ORM\Column(type: 'integer', options: [ 'default' => 1 ], columnDefinition: 'TINYINT(1) NOT NULL DEFAULT 1')]
    public int $user_account_type = 1;

    #[ORM\Column(type: 'boolean', options: [ 'default' => false ])]
    public bool $user_has_avatar = false;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    public ?string $user_remember_me_token = null;

    #[ORM\Column(type: 'bigint', nullable: true)]
    public ?string $user_suspension_timestamp = null;

    #[ORM\Column(type: 'datetime_immutable', columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    public DateTimeImmutable $user_last_login_timestamp;

    #[ORM\Column(type: 'integer', options: [ 'default' => 0 ], columnDefinition: 'TINYINT(1) NOT NULL DEFAULT 0')]
    public int $user_failed_logins = 0;

    /** The historical zero-date default was migrated to a nullable timestamp. */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    public ?DateTimeImmutable $user_last_failed_login = null;

    #[ORM\Column(type: 'string', length: 40, nullable: true)]
    public ?string $user_activation_hash = null;

    // columnDefinition pins the existing CHAR(40) — plain type:'string' with
    // a length generates VARCHAR, a real (if minor) type change.
    #[ORM\Column(type: 'string', length: 40, nullable: true, columnDefinition: 'CHAR(40) DEFAULT NULL')]
    public ?string $password_reset_hash = null;

    /** Same historical zero-date default issue as user_last_failed_login. */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    public ?DateTimeImmutable $user_password_reset_timestamp = null;

    // columnDefinition pins the existing TEXT — Doctrine's plain 'text' type
    // generates LONGTEXT, an unintended widening.
    #[ORM\Column(type: 'text', nullable: true, columnDefinition: 'TEXT DEFAULT NULL')]
    public ?string $user_provider_type = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    public ?string $user_fbid = null;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    public DateTimeImmutable $ModificationDate;

    /** @var Collection<int, Role> */
    #[ORM\ManyToMany(targetEntity: Role::class)]
    #[ORM\JoinTable(name: 'user_role')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    #[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'role_id')]
    public Collection $roles;

    public function __construct()
    {
        $this->user_last_login_timestamp = new DateTimeImmutable();
        $this->CreationDate = new DateTimeImmutable();
        $this->ModificationDate = new DateTimeImmutable();
        $this->roles = new ArrayCollection();
    }

    public static function create(
        string $username,
        string $email,
        string $passwordHash,
        bool $active = true,
    ): self {
        $user = new self();
        $user->user_name = $username;
        $user->user_email = $email;
        $user->user_password_hash = $passwordHash;
        $user->user_active = $active;

        return $user;
    }

    public function changeUsername(string $username): void
    {
        $this->user_name = $username;
    }

    public function changePasswordHash(string $hash): void
    {
        $this->user_password_hash = $hash;
    }

    public function setRememberMeToken(?string $token): void
    {
        $this->user_remember_me_token = $token;
    }

    public function setSessionId(?string $sessionId): void
    {
        $this->session_id = $sessionId;
    }

    public function connectFacebook(?string $facebookId): void
    {
        $this->user_fbid = $facebookId;
    }

    public function recordFailedLogin(?DateTimeImmutable $at = null): void
    {
        ++$this->user_failed_logins;
        $this->user_last_failed_login = $at ?? new DateTimeImmutable();
    }

    public function resetFailedLogins(): void
    {
        $this->user_failed_logins = 0;
        $this->user_last_failed_login = null;
    }

    public function markLoggedIn(string $sessionId, ?DateTimeImmutable $at = null): void
    {
        $this->session_id = $sessionId;
        $this->user_last_login_timestamp = $at ?? new DateTimeImmutable();
    }

    public function isLoginBlocked(?DateTimeImmutable $now = null): bool
    {
        if ($this->user_failed_logins < 3 || $this->user_last_failed_login === null) {
            return false;
        }

        return $this->user_last_failed_login > ($now ?? new DateTimeImmutable())->modify('-30 seconds');
    }

    public function setPasswordResetToken(string $hash, DateTimeImmutable $timestamp): void
    {
        $this->password_reset_hash = $hash;
        $this->user_password_reset_timestamp = $timestamp;
    }

    public function clearPasswordResetToken(): void
    {
        $this->password_reset_hash = null;
        $this->user_password_reset_timestamp = null;
    }

    public function addRole(Role $role): void
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }
    }

    public function hasRole(Role $role): bool
    {
        return $this->roles->contains($role);
    }

    public function removeRole(Role $role): void
    {
        $this->roles->removeElement($role);
    }
}
