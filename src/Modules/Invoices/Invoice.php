<?php

namespace PortalCMS\Modules\Invoices;

class Invoice
{
    public $id;
    public $contract_id;
    public $factuurnummer;
    public $status;
    public $mail_id;

    public function __construct(int $id = null, int $contract_id = null, int $factuurnummer = null, int $status = null, int $mail_id = null)
    {
        $this->id = $id;
        $this->contract_id = $contract_id;
        $this->factuurnummer = $factuurnummer;
        $this->status = $status;
        $this->mail_id = $mail_id;
    }
}
