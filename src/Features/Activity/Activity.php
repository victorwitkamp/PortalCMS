<?php

declare(strict_types=1);

namespace PortalCMS\Features\Activity;

use PortalCMS\Core\Http\RemoteAddress;
use PortalCMS\Features\Activity\Entity\Activity as ActivityEntry;
use PortalCMS\Features\Activity\Repository\ActivityRepository;
use PortalCMS\Features\Users\Entity\User;
use PortalCMS\Features\Users\Repository\UserRepository;

final class Activity
{
    public function __construct(
        private readonly ActivityRepository $activities,
        private readonly UserRepository $users,
        private readonly RemoteAddress $remoteAddress,
    ) {
    }

    /**
     * @return ActivityEntry[]
     */
    public function load(): array
    {
        return $this->activities->findRecent();
    }

    public function add(
        string $name,
        ?int $userId = null,
        ?string $details = null,
        ?string $userName = null,
        bool $flush = true,
    ): bool {
        if ($name === '') {
            return false;
        }

        $user = $userId !== null ? $this->users->find($userId) : null;
        $entry = ActivityEntry::record(
            $name,
            $user instanceof User ? $user : null,
            $userName,
            $this->remoteAddress->getIpAddress(),
            $details,
        );
        $this->activities->save($entry);
        if ($flush) {
            $this->activities->flush();
        }

        return true;
    }
}
