<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */
declare(strict_types=1);

namespace PortalCMS\Modules\Contracts;

/**
 * Class ContractContact
 * @package PortalCMS\Modules\Contracts
 */
class ContractContact
{
    public $name;
    public $address;
    public $zipCode;
    public $city;
    public $dateOfBirth;
    public $phonePrimary;
    public $phoneSecodary;
    public $emailAddress;
    public $citizenServiceNumber;

    /**
     * ContractContact constructor.
     * @param string|null $name
     * @param string|null $address
     * @param string|null $zipCode
     * @param string|null $city
     * @param string|null $dateOfBirth
     * @param string|null $phonePrimary
     * @param string|null $phoneSecodary
     * @param string|null $emailAddress
     * @param int|null    $citizenServiceNumber
     */
    public function __construct(string $name = null, string $address = null, string $zipCode = null, string $city = null, string $dateOfBirth = null, string $phonePrimary = null, string $phoneSecodary = null, string $emailAddress = null, int $citizenServiceNumber = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->dateOfBirth = $dateOfBirth;
        $this->phonePrimary = $phonePrimary;
        $this->phoneSecodary = $phoneSecodary;
        $this->emailAddress = $emailAddress;
        $this->citizenServiceNumber = $citizenServiceNumber;
    }
}
