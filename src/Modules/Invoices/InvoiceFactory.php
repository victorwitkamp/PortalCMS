<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

class InvoiceFactory
{
    public static function get(int $id) : ?Invoice
    {
        $mapped = InvoiceMapper::getById($id);
        if ($mapped !== null) {
            return new Invoice(
                (int) $mapped->id,
                (int) $mapped->contract_id,
                (int) $mapped->year,
                (int) $mapped->month,
                (int) $mapped->factuurnummer,
                (string) $mapped->factuurdatum,
                (int) $mapped->status,
                (int) $mapped->mail_id
            );
        }
        return null;
    }
}
