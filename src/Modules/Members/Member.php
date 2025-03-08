<?php
declare(strict_types=1);


namespace App\Modules\Members;

/**
 * Class Member
 * @package PortalCMS\Modules\Members
 */
class Member
{
    public ?int $id;
    public ?int $jaarlidmaatschap;
    public ?string $voorletters;
    public ?string $voornaam;
    public ?string $achternaam;
    public ?string $geboortedatum;
    public ?MemberAddress $address;
    public ?MemberContactDetails $contactDetails;
    public ?string $ingangsdatum;
    public ?string $geslacht;
    public ?MemberPreferences $preferences;
    public ?MemberPaymentDetails $paymentDetails;
    public ?string $creationDate;
    public ?string $modificationDate;

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
