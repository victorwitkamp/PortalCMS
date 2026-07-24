<?php

declare(strict_types=1);

namespace PortalCMS\Features\Contracts\Input;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ContractInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 50)]
        public string $band_naam,
        #[Assert\NotBlank]
        #[Assert\Length(max: 2)]
        public string $bandcode,
        public ?string $beuk_vertegenwoordiger = null,
        public ?string $bandleider_naam = null,
        public ?string $bandleider_adres = null,
        public ?string $bandleider_postcode = null,
        public ?string $bandleider_woonplaats = null,
        public ?string $bandleider_geboortedatum = null,
        public ?string $bandleider_telefoonnummer1 = null,
        public ?string $bandleider_telefoonnummer2 = null,
        #[Assert\Email]
        public ?string $bandleider_email = null,
        public ?string $bandleider_bsn = null,
        public ?string $huur_oefenruimte_nr = null,
        public ?string $huur_dag = null,
        public ?DateTimeImmutable $huur_start = null,
        public ?DateTimeImmutable $huur_einde = null,
        public ?string $huur_kast_nr = null,
        public ?string $kosten_ruimte = null,
        public ?string $kosten_kast = null,
        public ?string $kosten_borg = null,
        public ?DateTimeImmutable $contract_ingangsdatum = null,
        public ?DateTimeImmutable $contract_einddatum = null,
        public ?DateTimeImmutable $contract_datum = null,
    ) {
    }
}
