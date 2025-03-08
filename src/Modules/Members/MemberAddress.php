<?php
declare(strict_types=1);


namespace App\Modules\Members;

/**
 * Class MemberAddress
 * @package PortalCMS\Modules\Members
 */
class MemberAddress
{
    public ?string $adres;
    public ?string $postcode;
    public ?string $huisnummer;
    public ?string $woonplaats;

    public function __construct(string $adres = null, string $postcode = null, string $huisnummer = null, string $woonplaats = null)
    {
        $this->adres = $adres;
        $this->postcode = $postcode;
        $this->huisnummer = $huisnummer;
        $this->woonplaats = $woonplaats;
    }
}
