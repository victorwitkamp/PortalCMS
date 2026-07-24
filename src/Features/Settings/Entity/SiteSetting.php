<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Settings\Repository\SiteSettingRepository;

#[ORM\Entity(repositoryClass: SiteSettingRepository::class)]
#[ORM\Table(name: 'site_settings')]
class SiteSetting
{
    /** Was not declared PRIMARY KEY at all in the live schema — added in this migration (no duplicate/empty keys found). */
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 32)]
    public string $setting;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    public ?string $string_value = null;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    public DateTimeImmutable $ModificationDate;

    public function __construct()
    {
        $this->ModificationDate = new DateTimeImmutable();
    }

    public function changeValue(?string $value): void
    {
        $this->string_value = $value;
    }
}
