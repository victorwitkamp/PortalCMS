<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

/**
 * Class Invoice
 * @package PortalCMS\Modules\Invoices
 */
class Invoice
{
    public $id;
    public $contract_id;
    public $year;
    public $month;
    public $factuurnummer;
    public $factuurdatum;
    public $status;
    public $mail_id;

    public function __construct(int $id = null, int $contract_id = null, int $year = null, int $month = null, int $factuurnummer = null, string $factuurdatum = null, int $status = null, int $mail_id = null)
    {
        $this->id = $id;
        $this->contract_id = $contract_id;
        $this->year = $year;
        $this->month = $month;
        $this->factuurnummer = $factuurnummer;
        $this->factuurdatum = $factuurdatum;
        $this->status = $status;
        $this->mail_id = $mail_id;
    }
}
