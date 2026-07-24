<?php

declare(strict_types=1);

namespace PortalCMS\Features\Members\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Members\Repository\MemberRepository;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: 'members')]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $jaarlidmaatschap = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    public ?string $voorletters = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    public ?string $voornaam = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    public ?string $achternaam = null;

    // Left as varchar (not converted to DATE) — out of the scope identified
    // for this phase; only contracts'/invoices' date columns were flagged.
    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    public ?string $geboortedatum = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    public ?string $adres = null;

    #[ORM\Column(type: 'string', length: 6, nullable: true)]
    public ?string $postcode = null;

    #[ORM\Column(type: 'string', length: 6, nullable: true)]
    public ?string $huisnummer = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    public ?string $woonplaats = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    public ?string $telefoon_vast = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    public ?string $telefoon_mobiel = null;

    #[ORM\Column(type: 'string', length: 254, nullable: true)]
    public ?string $emailadres = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    public ?string $ingangsdatum = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    public ?string $geslacht = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $nieuwsbrief = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $vrijwilliger = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $vrijwilligeroptie1 = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $vrijwilligeroptie2 = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $vrijwilligeroptie3 = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $vrijwilligeroptie4 = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $vrijwilligeroptie5 = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    public ?string $betalingswijze = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    public ?string $iban = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    public ?string $machtigingskenmerk = null;

    #[ORM\Column(type: 'integer', nullable: true, options: [ 'default' => 0 ])]
    public ?int $status = 0;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    public ?string $opmerking = null;

    #[ORM\Column(name: 'CreationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')]
    public DateTimeImmutable $CreationDate;

    #[ORM\Column(name: 'ModificationDate', type: 'datetime_immutable', insertable: false, updatable: false, columnDefinition: 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    public DateTimeImmutable $ModificationDate;

    public function __construct()
    {
        $this->CreationDate = new DateTimeImmutable();
        $this->ModificationDate = new DateTimeImmutable();
    }
}
