<?php

declare(strict_types=1);

namespace PortalCMS\Features\Pages\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Pages\Repository\PageRepository;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ORM\Table(name: 'pages')]
class Page
{
    /** Was not declared PRIMARY KEY at all in the live schema — added in this migration (no duplicate/empty ids found). */
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 32)]
    public string $id;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    public ?string $name = null;

    // columnDefinition pins the existing TEXT — Doctrine's plain 'text' type
    // generates LONGTEXT, an unintended widening.
    #[ORM\Column(type: 'text', nullable: true, columnDefinition: 'TEXT DEFAULT NULL')]
    public ?string $content = null;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    public DateTimeImmutable $ModificationDate;

    public function __construct()
    {
        $this->ModificationDate = new DateTimeImmutable();
    }

    public function changeContent(string $content): void
    {
        $this->content = $content;
    }
}
