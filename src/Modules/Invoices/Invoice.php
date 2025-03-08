<?php


declare(strict_types=1);

namespace App\Modules\Invoices;

class Invoice
{
    public ?int $id;
    public ?int $contract_id;
    public ?int $factuurnummer;
    public ?int $status;
    public ?int $mail_id;

    public function __construct(int $id = null, int $contract_id = null, int $factuurnummer = null, int $status = null, int $mail_id = null)
    {
        $this->id = $id;
        $this->contract_id = $contract_id;
        $this->factuurnummer = $factuurnummer;
        $this->status = $status;
        $this->mail_id = $mail_id;
    }
}
