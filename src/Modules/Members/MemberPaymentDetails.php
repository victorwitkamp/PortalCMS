<?php
declare(strict_types=1);


namespace App\Modules\Members;

/**
 * Class MemberPaymentDetails
 * @package PortalCMS\Modules\Members
 */
class MemberPaymentDetails
{
    public ?string $betalingswijze;
    public ?string $iban;
    public ?string $machtigingskenmerk;
    public ?int $status;

    public function __construct(?string $betalingswijze, ?string $iban, ?string $machtigingskenmerk, ?int $status)
    {
        $this->betalingswijze = $betalingswijze;
        $this->iban = $iban;
        $this->machtigingskenmerk = $machtigingskenmerk;
        $this->status = $status;
    }
}
