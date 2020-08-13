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
    public $address;
    public $contactDetails;
    public $ingangsdatum;
    public $geslacht;
    public $preferences;
    public $paymentDetails;
    public $creationDate;
    public $modificationDate;

    /**
     * Member constructor.
     * @param int|null                  $id
     * @param int|null                  $jaarlidmaatschap
     * @param string|null               $voorletters
     * @param string|null               $voornaam
     * @param string|null               $achternaam
     * @param string|null               $geboortedatum
     * @param MemberAddress|null        $address
     * @param MemberContactDetails|null $contactDetails
     * @param string|null               $ingangsdatum
     * @param string|null               $geslacht
     * @param MemberPreferences|null    $preferences
     * @param MemberPaymentDetails|null $paymentDetails
     * @param string|null               $creationDate
     * @param string|null               $modificationDate
     */
    public function __construct(int $id = null, int $jaarlidmaatschap = null, string $voorletters = null, string $voornaam = null, string $achternaam = null, string $geboortedatum = null, MemberAddress $address = null, MemberContactDetails $contactDetails = null, string $ingangsdatum = null, string $geslacht = null, MemberPreferences $preferences = null, MemberPaymentDetails $paymentDetails = null, string $creationDate = null, string $modificationDate = null)
    {
        $this->id = $id;
        $this->jaarlidmaatschap = $jaarlidmaatschap;
        $this->voorletters = $voorletters;
        $this->voornaam = $voornaam;
        $this->achternaam = $achternaam;
        $this->geboortedatum = $geboortedatum;
        $this->address = $address;
        $this->contactDetails = $contactDetails;
        $this->ingangsdatum = $ingangsdatum;
        $this->geslacht = $geslacht;
        $this->preferences = $preferences;
        $this->paymentDetails = $paymentDetails;
        $this->creationDate = $creationDate;
        $this->modificationDate = $modificationDate;
    }
}
