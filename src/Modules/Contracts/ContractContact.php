<?php

declare(strict_types=1);

namespace App\Modules\Contracts;

class ContractContact
{
    public ?string $name;
    public ?string $address;
    public ?string $zipCode;
    public ?string $city;
    public ?string $dateOfBirth;
    public ?string $phonePrimary;
    public ?string $phoneSecodary;
    public ?string $emailAddress;
    public ?int $citizenServiceNumber;

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
