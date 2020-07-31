<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Modules\Members;

/**
 * Class Member
 * @package PortalCMS\Modules\Members
 */
class Member
{
    public $id;
    public $jaarlidmaatschap;
    public $voorletters;
    public $voornaam;
    public $achternaam;
    public $geboortedatum;
    public $memberAddress;
    public $memberContactDetails;
    public $ingangsdatum;
    public $geslacht;
    public $memberPreferences;
    public $memberPaymentDetails;

    public function __construct(
        int $id = null,
        int $jaarlidmaatschap = null,
        string $voorletters = null,
        string $voornaam = null,
        string $achternaam = null,
        string $geboortedatum = null,
        MemberAddress $memberAddress = null,
        MemberContactDetails $memberContactDetails = null,
        string $ingangsdatum = null,
        string $geslacht = null,
        MemberPreferences $memberPreferences = null,
        MemberPaymentDetails $memberPaymentDetails = null
    ) {
        $this->id = $id;
        $this->jaarlidmaatschap = $jaarlidmaatschap;
        $this->voorletters = $voorletters;
        $this->voornaam = $voornaam;
        $this->achternaam = $achternaam;
        $this->geboortedatum = $geboortedatum;
        $this->memberAddress = $memberAddress;
        $this->memberContactDetails = $memberContactDetails;
        $this->ingangsdatum = $ingangsdatum;
        $this->geslacht = $geslacht;
        $this->memberPreferences = $memberPreferences;
        $this->memberPaymentDetails = $memberPaymentDetails;
    }
}
