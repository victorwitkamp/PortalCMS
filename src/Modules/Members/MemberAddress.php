<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Modules\Members;

/**
 * Class MemberAddress
 * @package PortalCMS\Modules\Members
 */
class MemberAddress
{
    public $adres;
    public $postcode;
    public $huisnummer;
    public $woonplaats;

    public function __construct(string $adres = null, string $postcode = null, string $huisnummer = null, string $woonplaats = null)
    {
        $this->adres = $adres;
        $this->postcode = $postcode;
        $this->huisnummer = $huisnummer;
        $this->woonplaats = $woonplaats;
    }
}
