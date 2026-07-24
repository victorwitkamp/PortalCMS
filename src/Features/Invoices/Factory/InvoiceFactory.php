<?php

declare(strict_types=1);

namespace PortalCMS\Features\Invoices\Factory;

use DateTimeImmutable;
use PortalCMS\Core\View\Text;
use PortalCMS\Features\Contracts\Entity\Contract;
use PortalCMS\Features\Invoices\Entity\Invoice;

final class InvoiceFactory
{
    public function createForContract(
        Contract $contract,
        int $year,
        int $month,
        DateTimeImmutable $invoiceDate,
    ): Invoice {
        $invoice = new Invoice();
        $invoice->contract = $contract;
        $invoice->year = $year;
        $invoice->month = $month;
        $invoice->factuurnummer = sprintf('%04d%s%02d', $year, $contract->bandcode, $month);
        $invoice->factuurdatum = $invoiceDate;
        $invoice->status = Invoice::STATUS_DRAFT;

        $monthName = Text::get(sprintf('MONTH_%02d', $month));
        $roomCost = (int) $contract->kosten_ruimte;
        $storageCost = (int) $contract->kosten_kast;

        if ($roomCost > 0) {
            $invoice->addItem('Huur oefenruimte - ' . $monthName, $roomCost);
        }
        if ($storageCost > 0) {
            $invoice->addItem('Huur kast - ' . $monthName, $storageCost);
        }

        return $invoice;
    }
}
