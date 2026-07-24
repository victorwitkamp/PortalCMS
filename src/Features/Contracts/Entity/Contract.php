<?php

declare(strict_types=1);

namespace PortalCMS\Features\Contracts\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use PortalCMS\Features\Contracts\Repository\ContractRepository;

#[ORM\Entity(repositoryClass: ContractRepository::class)]
#[ORM\Table(name: 'contracts')]
class Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string', length: 2, nullable: true)]
    public ?string $bandcode = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    public ?string $beuk_vertegenwoordiger = null;

    #[ORM\Column(type: 'string', length: 50)]
    public string $band_naam;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    public ?string $bandleider_naam = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    public ?string $bandleider_adres = null;

    #[ORM\Column(type: 'string', length: 6, nullable: true)]
    public ?string $bandleider_postcode = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    public ?string $bandleider_woonplaats = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    public ?string $bandleider_geboortedatum = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    public ?string $bandleider_telefoonnummer1 = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    public ?string $bandleider_telefoonnummer2 = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    public ?string $bandleider_email = null;

    #[ORM\Column(type: 'string', length: 9, nullable: true)]
    public ?string $bandleider_bsn = null;

    #[ORM\Column(type: 'string', length: 1, nullable: true)]
    public ?string $huur_oefenruimte_nr = null;

    #[ORM\Column(type: 'string', length: 9, nullable: true)]
    public ?string $huur_dag = null;

    // Doctrine's non-immutable TimeType/DateType strictly reject
    // DateTimeImmutable at write time (verified: throws InvalidType) even
    // though both implement DateTimeInterface — use the _immutable DBAL
    // types instead, which map to the identical underlying SQL TIME/DATE
    // column, just hydrate/accept DateTimeImmutable.

    /** Was varchar(5) "HH:MM"; corrected to a real TIME column in this migration. */
    #[ORM\Column(type: 'time_immutable', nullable: true)]
    public ?DateTimeImmutable $huur_start = null;

    #[ORM\Column(type: 'time_immutable', nullable: true)]
    public ?DateTimeImmutable $huur_einde = null;

    #[ORM\Column(type: 'string', length: 1, nullable: true)]
    public ?string $huur_kast_nr = null;

    /** Was varchar(3); corrected to DECIMAL in this migration. */
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    public ?string $kosten_ruimte = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    public ?string $kosten_kast = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    public ?string $kosten_totaal = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    public ?string $kosten_borg = null;

    /** Was varchar(10) "YYYY-MM-DD"; corrected to a real DATE column in this migration. */
    #[ORM\Column(type: 'date_immutable', nullable: true)]
    public ?DateTimeImmutable $contract_ingangsdatum = null;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    public ?DateTimeImmutable $contract_einddatum = null;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    public ?DateTimeImmutable $contract_datum = null;

    // insertable/updatable: false — MySQL's own DEFAULT CURRENT_TIMESTAMP /
    // ON UPDATE CURRENT_TIMESTAMP manage these entirely (matching the
    // original raw-SQL mapper, which never included these columns in its
    // INSERT/UPDATE statements either); Doctrine never writes them, so a
    // placeholder default just keeps the typed property initialized.
    // columnDefinition also pins the exact DDL so Doctrine's schema tool
    // doesn't try to convert these to DATETIME (its default mapping for
    // "datetime_immutable" is DATETIME, not TIMESTAMP).
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
