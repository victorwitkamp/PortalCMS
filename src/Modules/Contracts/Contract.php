<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */
declare(strict_types=1);

namespace PortalCMS\Modules\Contracts;

/**
 * Class Contract
 * @package PortalCMS\Modules\Contracts
 */
class Contract
{
    public $id;
    public $accountManager;
    public $name;
    public $code;
    public $contractContact;
    public $rehearsalRoomNumber;
    public $day;
    public $startTime;
    public $endTime;
    public $storageNumber;
    public $rehearsalRoomMonthlyCost;
    public $storageMonthlyCost;
    public $totalMonthlyCost;
    public $bailCost;
    public $startDate;
    public $endDate;
    public $contractDate;

    /**
     * Contract constructor.
     * @param int|null             $id
     * @param string|null          $accountManager
     * @param string|null          $name
     * @param string|null          $code
     * @param ContractContact|null $contractContact
     * @param int|null             $rehearsalRoomNumber
     * @param string|null          $day
     * @param string|null          $startTime
     * @param string|null          $endTime
     * @param int|null             $storageNumber
     * @param int|null             $rehearsalRoomMonthlyCost
     * @param int|null             $storageMonthlyCost
     * @param int|null             $totalMonthlyCost
     * @param int|null             $bailCost
     * @param string|null          $startDate
     * @param string|null          $endDate
     * @param string|null          $contractDate
     */
    public function __construct(int $id = null, string $accountManager = null, string $name = null, string $code = null, ContractContact $contractContact = null, int $rehearsalRoomNumber = null, string $day = null, string $startTime = null, string $endTime = null, int $storageNumber = null, int $rehearsalRoomMonthlyCost = null, int $storageMonthlyCost = null, int $totalMonthlyCost = null, int $bailCost = null, string $startDate = null, string $endDate = null, string $contractDate = null)
    {
        $this->id = $id;
        $this->accountManager = $accountManager;
        $this->name = $name;
        $this->code = $code;
        $this->contractContact = $contractContact;
        $this->rehearsalRoomNumber = $rehearsalRoomNumber;
        $this->day = $day;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->storageNumber = $storageNumber;
        $this->rehearsalRoomMonthlyCost = $rehearsalRoomMonthlyCost;
        $this->storageMonthlyCost = $storageMonthlyCost;
        $this->totalMonthlyCost = $totalMonthlyCost;
        $this->bailCost = $bailCost;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->contractDate = $contractDate;
    }
}
