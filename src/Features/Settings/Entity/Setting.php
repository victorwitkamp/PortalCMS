<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Settings\Repository\SettingRepository;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
#[ORM\Table(name: 'site_settings')]
class Setting
{
    #[ORM\Id]
    #[ORM\Column(name: 'setting', type: 'string', length: 32)]
    private string $name;

    #[ORM\Column(name: 'string_value', type: 'string', length: 64, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', insertable: false, updatable: false, options: [ 'default' => 'CURRENT_TIMESTAMP' ], columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    private DateTimeImmutable $modifiedAt;

    public function __construct()
    {
        $this->modifiedAt = new DateTimeImmutable();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function changeValue(?string $value): void
    {
        $this->value = $value;
    }

    public function modifiedAt(): DateTimeImmutable
    {
        return $this->modifiedAt;
    }
}
