<?php

declare(strict_types=1);

namespace App\Modules\Contracts;

class Contract
{
    public ?int $id;
    public ?string $accountManager;
    public ?string $name;
    public ?string $code;
    public ?ContractContact $contractContact;
    public ?int $rehearsalRoomNumber;
    public ?string $day;
    public ?string $startTime;
    public ?string $endTime;
    public ?int $storageNumber;
    public ?int $rehearsalRoomMonthlyCost;
    public ?int $storageMonthlyCost;
    public ?int $totalMonthlyCost;
    public ?int $bailCost;
    public ?string $startDate;
    public ?string $endDate;
    public ?string $contractDate;

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
