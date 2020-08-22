<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Modules\Invoices;

use PortalCMS\Core\View\PDF;
use PortalCMS\Modules\Contracts\Contract;

class InvoicePDF extends PDF
{
    public $invoice;
    public $invoiceitems;
    public $contract;

    public function __construct(Invoice $invoice, array $invoiceitems, Contract $contract)
    {
        parent::__construct();
        $this->invoice = $invoice;
        $this->invoiceitems = $invoiceitems;
        $this->contract = $contract;
    }

    public function initHeader()
    {
        $this->SetTitle('Factuur ' . $this->invoice->factuurnummer);
        $this->SetXY(165, 15);
        $this->Image(DIR_IMG . 'logo_new_280px.jpg', '', '', 25, 25, '', '', 'T');
        $this->SetXY(120, 60);
        $this->MultiCell(0, 2, "Poppodium de Beuk\n1e Barendrechtseweg 53-55\n2992XE, Barendrecht\n\nKVK: 40341794\nIBAN: NL19RABO1017541353", $border = 0, $align = 'R');
        $this->SetXY(20, 70);
        $this->SetFont('dejavusans', 'B', 11, '', true);
        $this->Write(0, $this->contract->name . "\n", '', 0, 'L', true);
        $this->SetFont('dejavusans', '', 11, '', true);
        $this->SetX(20);
        $this->Write(0, 'T.a.v. ' . $this->contract->contractContact->name . "\n", '', 0, 'L', true);
        $this->SetX(20);
        $this->Write(0, $this->contract->contractContact->address . "\n", '', 0, 'L', true);
        $this->SetX(20);
        $this->Write(0, $this->contract->contractContact->zipCode . ' ' . $this->contract->contractContact->city, '', 0, 'L', true);
        $this->SetXY(20, 110);
        $this->SetFont('dejavusans', 'B', 11, '', true);
        $this->Write(0, 'Factuur', '', 0, 'L', true);
        $this->SetXY(20, 120);
        $this->SetFont('dejavusans', '', 11, '', true);
        $this->Write(0, 'Factuurnummer: ' . $this->invoice->factuurnummer, '', 0, 'L', true);
        $this->SetXY(120, 120);
        $this->Write(0, 'Factuurdatum: ' . date('d-m-Y', strtotime($this->invoice->factuurdatum)), '', 0, 'R', true);
        $this->SetXY(20, 140);
    }

    public function initContent()
    {
        $this->SetFont('dejavusans', 'B', 11, '', true);
        $this->Write(0, 'Omschrijving', '', 0, 'L');
        $this->SetX(150);
        $this->Write(0, 'Bedrag', '', 0, 'R');
        $this->Ln();
        $this->SetFont('dejavusans', '', 11, '', true);

        $totaalbedrag = 0;
        if (!empty($this->invoiceitems)) {
            foreach ($this->invoiceitems as $invoiceitem) {
                $this->SetX(20);
                $this->Write(0, $invoiceitem->name, '', 0, 'L');
                $this->SetX(165);
                $this->Write(0, '€', '', 0, 'L');
                $this->SetX(150);
                $this->Write(0, $invoiceitem->price, '', 0, 'R');
                $this->Ln();
                $totaalbedrag += $invoiceitem->price;
            }
        }

        $this->Ln();
        $this->SetX(20);
        $this->Write(0, 'Totaal:', '', 0, 'L');
        $this->SetX(165);
        $this->Write(0, '€', '', 0, 'L');
        $this->SetX(150);
        $this->Write(0, $totaalbedrag . "\n\n\n", '', 0, 'R');
        $this->SetX(20);
    }

    public function initFooter()
    {
        $this->SetX(20);
        $this->Write(0, 'Wij verzoeken u het bedrag binnen 14 dagen over te maken naar', '', 0, '', true);
        $this->SetX(20);
        $this->Write(0, 'NL19 RABO 1017 5413 53 o.v.v. het factuurnummer t.n.v. SOCIETEIT DE BEUK.' . "\n", '', 0, 'L', true);
        $this->SetX(20);
        $this->Write(0, 'Neem voor vragen over facturatie contact op met penningmeester@beukonline.nl.' . "\n\n", '', 0, 'L', true);
        $this->SetX(20);
        $this->Write(0, 'Met vriendelijke groet,' . "\n\n", '', 0, 'L', true);
        $this->SetX(20);
        $this->Write(0, 'De penningmeester van Poppodium de Beuk.', '', 0, 'L', true);
    }
}
