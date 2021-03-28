<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Modules\Members;

/**
 * Class MemberPaymentDetails
 * @package PortalCMS\Modules\Members
 */
class MemberPaymentDetails
{
    public $betalingswijze;
    public $iban;
    public $machtigingskenmerk;
    public $status;

    public function __construct(?string $betalingswijze, ?string $iban, ?string $machtigingskenmerk, ?int $status)
    {
        $this->betalingswijze = $betalingswijze;
        $this->iban = $iban;
        $this->machtigingskenmerk = $machtigingskenmerk;
        $this->status = $status;
    }
}
