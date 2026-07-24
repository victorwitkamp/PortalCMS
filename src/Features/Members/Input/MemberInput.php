<?php

declare(strict_types=1);

namespace PortalCMS\Features\Members\Input;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class MemberInput
{
    public function __construct(
        #[Assert\Positive]
        public int $jaarlidmaatschap,
        public ?string $voorletters = null,
        public ?string $voornaam = null,
        #[Assert\NotBlank]
        public string $achternaam = '',
        public ?string $geboortedatum = null,
        public ?string $adres = null,
        public ?string $postcode = null,
        public ?string $huisnummer = null,
        public ?string $woonplaats = null,
        public ?string $telefoon_vast = null,
        public ?string $telefoon_mobiel = null,
        #[Assert\Email]
        public ?string $emailadres = null,
        public ?string $ingangsdatum = null,
        public ?string $geslacht = null,
        public ?bool $nieuwsbrief = null,
        public ?bool $vrijwilliger = null,
        public ?bool $vrijwilligeroptie1 = null,
        public ?bool $vrijwilligeroptie2 = null,
        public ?bool $vrijwilligeroptie3 = null,
        public ?bool $vrijwilligeroptie4 = null,
        public ?bool $vrijwilligeroptie5 = null,
        public ?string $betalingswijze = null,
        public ?string $iban = null,
        public ?string $machtigingskenmerk = null,
        public int $status = 0,
    ) {
    }
}
