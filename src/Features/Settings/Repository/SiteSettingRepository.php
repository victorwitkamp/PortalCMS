<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Settings\Entity\SiteSetting;

/**
 * @extends EntityRepository<SiteSetting>
 */
final class SiteSettingRepository extends EntityRepository
{
    public function findSetting(string $setting): ?SiteSetting
    {
        $entity = $this->find($setting);
        return $entity instanceof SiteSetting ? $entity : null;
    }

    public function findValue(string $setting): ?string
    {
        return $this->findSetting($setting)?->string_value;
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
