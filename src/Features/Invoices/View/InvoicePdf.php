<?php

declare(strict_types=1);

namespace PortalCMS\Features\Invoices\View;

use PortalCMS\Features\Contracts\Entity\Contract;
use PortalCMS\Features\Invoices\Entity\Invoice;
use PortalCMS\Features\Settings\SiteSetting;
use RuntimeException;
use TCPDF;

final class InvoicePdf
{
    public function __construct(private readonly SiteSetting $settings)
    {
        $this->configureTcpdf();
    }

    public function render(Invoice $invoice, Contract $contract): string
    {
        $pdf = $this->document();
        $this->writeHeader($pdf, $invoice, $contract);
        $this->writeItems($pdf, $invoice);
        $this->writeFooter($pdf);

        return $pdf->Output($invoice->factuurnummer . '.pdf', 'S');
    }

    public function write(Invoice $invoice, Contract $contract): string
    {
        $path = $this->path($invoice);
        if (is_file($path)) {
            throw new RuntimeException('Bestand bestaat al.');
        }
        if (!is_dir(dirname($path)) && !mkdir(dirname($path), 0775, true) && !is_dir(dirname($path))) {
            throw new RuntimeException('De factuurmap kon niet worden aangemaakt.');
        }
        if (file_put_contents($path, $this->render($invoice, $contract), LOCK_EX) === false) {
            throw new RuntimeException('De PDF kon niet worden opgeslagen.');
        }

        return $path;
    }

    public function remove(Invoice $invoice): bool
    {
        $path = $this->path($invoice);
        return !is_file($path) || unlink($path);
    }

    public function path(Invoice $invoice): string
    {
        return DIR_ROOT . 'content/invoices/' . $invoice->factuurnummer . '.pdf';
    }

    private function document(): TCPDF
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', true);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCreator((string) ($this->settings->get('site_name') ?? 'PortalCMS'));
        $pdf->SetAuthor((string) ($this->settings->get('site_name') ?? 'PortalCMS'));
        $pdf->SetDefaultMonospacedFont('courier');
        $pdf->SetMargins(15, 50, 15);
        $pdf->setHeaderMargin(10);
        $pdf->setFooterMargin(10);
        $pdf->SetAutoPageBreak(true, 25);
        $pdf->setImageScale(1.25);
        $pdf->setFontSubsetting(false);
        $pdf->AddPage();

        return $pdf;
    }

    private function writeHeader(TCPDF $pdf, Invoice $invoice, Contract $contract): void
    {
        $pdf->SetTitle('Factuur ' . $invoice->factuurnummer);
        $pdf->SetXY(165, 15);
        $pdf->Image(DIR_IMG . 'logo_new_280px.jpg', '', '', 25, 25, '', '', 'T');
        $pdf->SetXY(120, 60);
        $pdf->MultiCell(
            0,
            2,
            "Poppodium de Beuk\n1e Barendrechtseweg 53-55\n2992XE, Barendrecht\n\n"
            . "KVK: 40341794\nIBAN: NL19RABO1017541353",
            0,
            'R',
        );
        $pdf->SetXY(20, 70);
        $pdf->SetFont('dejavusans', 'B', 11, '', true);
        $pdf->Write(0, (string) $contract->band_naam . "\n", '', 0, 'L', true);
        $pdf->SetFont('dejavusans', '', 11, '', true);
        $pdf->SetX(20);
        $pdf->Write(0, 'T.a.v. ' . $contract->bandleider_naam . "\n", '', 0, 'L', true);
        $pdf->SetX(20);
        $pdf->Write(0, $contract->bandleider_adres . "\n", '', 0, 'L', true);
        $pdf->SetX(20);
        $pdf->Write(
            0,
            $contract->bandleider_postcode . ' ' . $contract->bandleider_woonplaats,
            '',
            0,
            'L',
            true,
        );
        $pdf->SetXY(20, 110);
        $pdf->SetFont('dejavusans', 'B', 11, '', true);
        $pdf->Write(0, 'Factuur', '', 0, 'L', true);
        $pdf->SetXY(20, 120);
        $pdf->SetFont('dejavusans', '', 11, '', true);
        $pdf->Write(0, 'Factuurnummer: ' . $invoice->factuurnummer, '', 0, 'L', true);
        $pdf->SetXY(120, 120);
        $pdf->Write(0, 'Factuurdatum: ' . $invoice->factuurdatum->format('d-m-Y'), '', 0, 'R', true);
        $pdf->SetXY(20, 140);
    }

    private function writeItems(TCPDF $pdf, Invoice $invoice): void
    {
        $pdf->SetFont('dejavusans', 'B', 11, '', true);
        $pdf->Write(0, 'Omschrijving', '', 0, 'L');
        $pdf->SetX(150);
        $pdf->Write(0, 'Bedrag', '', 0, 'R');
        $pdf->Ln();
        $pdf->SetFont('dejavusans', '', 11, '', true);

        foreach ($invoice->items() as $item) {
            $pdf->SetX(20);
            $pdf->Write(0, $item->name, '', 0, 'L');
            $pdf->SetX(165);
            $pdf->Write(0, 'EUR', '', 0, 'L');
            $pdf->SetX(150);
            $pdf->Write(0, (string) $item->price, '', 0, 'R');
            $pdf->Ln();
        }

        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Write(0, 'Totaal:', '', 0, 'L');
        $pdf->SetX(165);
        $pdf->Write(0, 'EUR', '', 0, 'L');
        $pdf->SetX(150);
        $pdf->Write(0, $invoice->total() . "\n\n\n", '', 0, 'R');
        $pdf->SetX(20);
    }

    private function writeFooter(TCPDF $pdf): void
    {
        $pdf->SetX(20);
        $pdf->Write(0, 'Wij verzoeken u het bedrag binnen 14 dagen over te maken naar', '', 0, '', true);
        $pdf->SetX(20);
        $pdf->Write(
            0,
            "NL19 RABO 1017 5413 53 o.v.v. het factuurnummer t.n.v. SOCIETEIT DE BEUK.\n",
            '',
            0,
            'L',
            true,
        );
        $pdf->SetX(20);
        $pdf->Write(
            0,
            "Neem voor vragen over facturatie contact op met penningmeester@beukonline.nl.\n\n",
            '',
            0,
            'L',
            true,
        );
        $pdf->SetX(20);
        $pdf->Write(0, "Met vriendelijke groet,\n\n", '', 0, 'L', true);
        $pdf->SetX(20);
        $pdf->Write(0, 'De penningmeester van Poppodium de Beuk.', '', 0, 'L', true);
    }

    private function configureTcpdf(): void
    {
        if (!defined('K_TCPDF_EXTERNAL_CONFIG')) {
            define('K_TCPDF_EXTERNAL_CONFIG', true);
            define('K_PATH_MAIN', DIR_VENDOR . 'tecnickcom/tcpdf/');
            define('K_PATH_URL', DIR_VENDOR . 'tecnickcom/tcpdf/');
            define('K_PATH_FONTS', K_PATH_MAIN . 'fonts/');
            define('K_PATH_IMAGES', DIR_IMG);
            define('K_PATH_CACHE', DIR_TEMP);
            define('K_BLANK_IMAGE', '_blank.png');
            define('PDF_PAGE_FORMAT', 'A4');
            define('PDF_PAGE_ORIENTATION', 'P');
            define('PDF_UNIT', 'mm');
            define('PDF_MARGIN_HEADER', 10);
            define('PDF_MARGIN_FOOTER', 10);
            define('PDF_MARGIN_TOP', 50);
            define('PDF_MARGIN_BOTTOM', 25);
            define('PDF_MARGIN_LEFT', 15);
            define('PDF_MARGIN_RIGHT', 15);
            define('PDF_FONT_NAME_MAIN', 'helvetica');
            define('PDF_FONT_SIZE_MAIN', 10);
            define('PDF_FONT_NAME_DATA', 'helvetica');
            define('PDF_FONT_SIZE_DATA', 8);
            define('PDF_FONT_MONOSPACED', 'courier');
            define('PDF_IMAGE_SCALE_RATIO', 1.25);
            define('HEAD_MAGNIFICATION', 1.1);
            define('K_CELL_HEIGHT_RATIO', 1.25);
            define('K_TITLE_MAGNIFICATION', 1.3);
            define('K_SMALL_RATIO', 2 / 3);
            define('K_THAI_TOPCHARS', true);
            define('K_TCPDF_CALLS_IN_HTML', true);
            define('K_TCPDF_THROW_EXCEPTION_ERROR', false);
        }
    }
}
