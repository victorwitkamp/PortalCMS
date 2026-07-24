<?php

declare(strict_types=1);

namespace PortalCMS\Features\Activity\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Activity\Repository\ActivityRepository;
use PortalCMS\Features\Users\Entity\User;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[ORM\Table(name: 'activity')]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    /** Was an unenforced plain int column; added as a real FK in this migration (no orphaned rows found). */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id', nullable: true)]
    public ?User $user = null;

    /** Mirrors `user` above as a plain scalar for existing call sites reading `->user_id` directly. */
    #[ORM\Column(name: 'user_id', type: 'integer', nullable: true, insertable: false, updatable: false)]
    public ?int $user_id = null;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    public ?string $user_name = null;

    /** Was varchar(15) (IPv4-only); widened to varchar(45) in this migration to actually fit IPv6. */
    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    public ?string $ip_address = null;

    #[ORM\Column(type: 'string', length: 32)]
    public string $activity;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    public ?string $details = null;

    public function __construct()
    {
        $this->CreationDate = new DateTimeImmutable();
    }

    public static function record(
        string $name,
        ?User $user,
        ?string $userName,
        ?string $ipAddress,
        ?string $details,
    ): self {
        $activity = new self();
        $activity->activity = $name;
        $activity->user = $user;
        $activity->user_name = $userName;
        $activity->ip_address = $ipAddress;
        $activity->details = $details;

        return $activity;
    }
}
