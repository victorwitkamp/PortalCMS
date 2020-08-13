<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\View;

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Session\Session;
use TCPDF;
use function define;

/**
 * Class PDF
 * @package PortalCMS\Core\View
 */
class PDF
{
    public static $defined = false;

    /**
     * @var mixed variable to collect errors
     */
    public static $error;

    /**
     * @param $invoice
     * @param $invoiceitems
     * @param $contract
     * @return mixed
     */
    public static function renderInvoice(object $invoice, array $invoiceitems, object $contract)
    {
        if (self::$defined === false) {
            self::config();
        }
        $pdf = self::configPDF();
        $pdf = self::createInvoice($pdf, $invoice, $invoiceitems, $contract);
        ob_end_clean();
        return $pdf->Output($invoice->factuurnummer . '.pdf');
    }

    public static function config()
    {
        define('K_TCPDF_EXTERNAL_CONFIG', true);
        define('K_PATH_MAIN', DIR_VENDOR . 'tecnickcom/tcpdf/');
        define('K_PATH_URL', DIR_VENDOR . 'tecnickcom/tcpdf/');
        define('K_PATH_FONTS', K_PATH_MAIN . 'fonts/');
        define('K_PATH_IMAGES', DIR_IMG);
        define('PDF_HEADER_LOGO', 'logo.jpg');
        define('PDF_HEADER_LOGO_WIDTH', 30);
        define('K_PATH_CACHE', DIR_TEMP);
        define('K_BLANK_IMAGE', '_blank.png');
        define('PDF_PAGE_FORMAT', 'A4');
        define('PDF_PAGE_ORIENTATION', 'P');
        define('PDF_CREATOR', SiteSetting::get('site_name'));
        define('PDF_AUTHOR', SiteSetting::get('site_name'));
        define('PDF_HEADER_TITLE', 'Factuur');
        define('PDF_HEADER_STRING', "Poppodium de Beuk\nbeukonline.nl");
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
        self::$defined = true;
    }

    /**
     * @return TCPDF
     */
    /**
     * @return TCPDF
     */
    public static function configPDF(): TCPDF
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->setFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(false);
        $pdf->AddPage();
        return $pdf;
    }

    /**
     * @param TCPDF  $pdf
     * @param object $invoice
     * @param array  $invoiceitems
     * @param object $contract
     * @return TCPDF
     */
    /**
     * @param TCPDF  $pdf
     * @param object $invoice
     * @param array  $invoiceitems
     * @param object $contract
     * @return TCPDF
     */
    /**
     * @param TCPDF  $pdf
     * @param object $invoice
     * @param array  $invoiceitems
     * @param object $contract
     * @return TCPDF
     */
    public static function createInvoice(TCPDF $pdf, object $invoice, array $invoiceitems, object $contract): TCPDF
    {
        return self::setInvoiceFooter(self::setInvoiceContent(self::setInvoiceHeader($pdf, $invoice, $contract), $invoiceitems));
    }

    /**
     * @param TCPDF $pdf
     * @return TCPDF
     */
    /**
     * @param TCPDF $pdf
     * @return TCPDF
     */
    /**
     * @param TCPDF $pdf
     * @return TCPDF
     */
    public static function setInvoiceFooter(TCPDF $pdf): TCPDF
    {
        $pdf->SetX(20);
        $pdf->Write(0, 'Wij verzoeken u het bedrag binnen 14 dagen over te maken naar', '', 0, '', true);
        $pdf->SetX(20);
        $pdf->Write(0, 'NL19 RABO 1017 5413 53 o.v.v. het factuurnummer t.n.v. SOCIETEIT DE BEUK.' . "\n", '', 0, 'L', true);
        $pdf->SetX(20);
        $pdf->Write(0, 'Neem voor vragen over facturatie contact op met penningmeester@beukonline.nl.' . "\n\n", '', 0, 'L', true);
        $pdf->SetX(20);
        $pdf->Write(0, 'Met vriendelijke groet,' . "\n\n", '', 0, 'L', true);
        $pdf->SetX(20);
        $pdf->Write(0, 'De penningmeester van Poppodium de Beuk.', '', 0, 'L', true);
        return $pdf;
    }

    /**
     * @param TCPDF $pdf
     * @param       $invoiceitems
     * @return TCPDF
     */
    /**
     * @param TCPDF $pdf
     * @param       $invoiceitems
     * @return TCPDF
     */
    /**
     * @param TCPDF $pdf
     * @param       $invoiceitems
     * @return TCPDF
     */
    public static function setInvoiceContent(TCPDF $pdf, $invoiceitems): TCPDF
    {
        $pdf->SetFont('dejavusans', 'B', 11, '', true);
        $pdf->Write(0, 'Omschrijving', '', 0, 'L');
        $pdf->SetX(150);
        $pdf->Write(0, 'Bedrag', '', 0, 'R');
        $pdf->Ln();
        $pdf->SetFont('dejavusans', '', 11, '', true);

        $totaalbedrag = 0;
        if (!empty($invoiceitems)) {
            foreach ($invoiceitems as $invoiceitem) {
                $pdf->SetX(20);
                $pdf->Write(0, $invoiceitem->name, '', 0, 'L');
                $pdf->SetX(165);
                $pdf->Write(0, '€', '', 0, 'L');
                $pdf->SetX(150);
                $pdf->Write(0, $invoiceitem->price, '', 0, 'R');
                $pdf->Ln();
                $totaalbedrag += $invoiceitem->price;
            }
        }

        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Write(0, 'Totaal:', '', 0, 'L');
        $pdf->SetX(165);
        $pdf->Write(0, '€', '', 0, 'L');
        $pdf->SetX(150);
        $pdf->Write(0, $totaalbedrag . "\n\n\n", '', 0, 'R');
        $pdf->SetX(20);
        return $pdf;
    }

    /**
     * @param TCPDF $pdf
     * @param       $invoice
     * @param       $contract
     * @return TCPDF
     */
    /**
     * @param TCPDF $pdf
     * @param       $invoice
     * @param       $contract
     * @return TCPDF
     */
    /**
     * @param TCPDF $pdf
     * @param       $invoice
     * @param       $contract
     * @return TCPDF
     */
    public static function setInvoiceHeader(TCPDF $pdf, $invoice, $contract): TCPDF
    {
        $pdf->SetTitle('Factuur ' . $invoice->factuurnummer);
        $pdf->SetXY(165, 15);
        $pdf->Image(DIR_IMG . 'logo_new_280px.jpg', '', '', 25, 25, '', '', 'T');
        $pdf->SetXY(120, 60);
        $pdf->MultiCell(0, 2, "Poppodium de Beuk\n1e Barendrechtseweg 53-55\n2992XE, Barendrecht\n\nKVK: 40341794\nIBAN: NL19RABO1017541353", $border = 0, $align = 'R');
        $pdf->SetXY(20, 70);
        $pdf->SetFont('dejavusans', 'B', 11, '', true);
        $pdf->Write(0, $contract->band_naam . "\n", '', 0, 'L', true);
        $pdf->SetFont('dejavusans', '', 11, '', true);
        $pdf->SetX(20);
        $pdf->Write(0, 'T.a.v. ' . $contract->bandleider_naam . "\n", '', 0, 'L', true);
        $pdf->SetX(20);
        $pdf->Write(0, $contract->bandleider_adres . "\n", '', 0, 'L', true);
        $pdf->SetX(20);
        $pdf->Write(0, $contract->bandleider_postcode . ' ' . $contract->bandleider_woonplaats, '', 0, 'L', true);
        $pdf->SetXY(20, 110);
        $pdf->SetFont('dejavusans', 'B', 11, '', true);
        $pdf->Write(0, 'Factuur', '', 0, 'L', true);
        $pdf->SetXY(20, 120);
        $pdf->SetFont('dejavusans', '', 11, '', true);
        $pdf->Write(0, 'Factuurnummer: ' . $invoice->factuurnummer, '', 0, 'L', true);
        $pdf->SetXY(120, 120);
        $pdf->Write(0, 'Factuurdatum: ' . date('d-m-Y', strtotime($invoice->factuurdatum)), '', 0, 'R', true);
        $pdf->SetXY(20, 140);
        return $pdf;
    }

    /**
     * @param object $invoice
     * @param array  $invoiceitems
     * @param object $contract
     * @return bool
     */
    /**
     * @param object $invoice
     * @param array  $invoiceitems
     * @param object $contract
     * @return bool
     */
    /**
     * @param object $invoice
     * @param array  $invoiceitems
     * @param object $contract
     * @return bool
     */
    public static function writeInvoice(object $invoice, array $invoiceitems, object $contract): bool
    {
        if (self::$defined === false) {
            self::config();
        }
        $pdf = self::configPDF();
        $pdf = self::createInvoice($pdf, $invoice, $invoiceitems, $contract);

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . 'content/invoices/' . $invoice->factuurnummer . '.pdf')) {
            Session::add('feedback_negative', 'Bestand bestaat al.');
        } else {
            // ob_end_clean();
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] . 'content/invoices/' . $invoice->factuurnummer . '.pdf', 'F');
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . 'content/invoices/' . $invoice->factuurnummer . '.pdf')) {
                return true;
            }
        }
        return false;
    }
}
