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
    public $factuurnummer;
    public $status;
    public $mail_id;

    /**
     * Invoice constructor.
     * @param int|null $id
     * @param int|null $contract_id
     * @param int|null $factuurnummer
     * @param int|null $status
     * @param int|null $mail_id
     */
    public function __construct(int $id = null, int $contract_id = null, int $factuurnummer = null, int $status = null, int $mail_id = null)
    {
        $this->id = $id;
        $this->contract_id = $contract_id;
        $this->factuurnummer = $factuurnummer;
        $this->status = $status;
        $this->mail_id = $mail_id;
    }
}
