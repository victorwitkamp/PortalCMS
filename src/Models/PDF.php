<?php

class PDF
{
    /**
     * @var mixed variable to collect errors
     */
    public static $error;

    public static function configPDF()
    {
        define('K_TCPDF_EXTERNAL_CONFIG', true);
        define('K_PATH_MAIN', DIR_VENDOR.'tecnickcom/tcpdf/');
        define('K_PATH_URL', DIR_VENDOR.'tecnickcom/tcpdf/');
        define('K_PATH_FONTS', K_PATH_MAIN.'fonts/');
        define('K_PATH_IMAGES', dirname(__FILE__).'/../images/');
        define('PDF_HEADER_LOGO', 'logo.jpg');
        define('PDF_HEADER_LOGO_WIDTH', 30);
        define('K_PATH_CACHE', DIR_TEMP);
        define('K_BLANK_IMAGE', '_blank.png');
        define('PDF_PAGE_FORMAT', 'A4');
        define('PDF_PAGE_ORIENTATION', 'P');
        define('PDF_CREATOR', SiteSetting::getStaticSiteSetting('site_name'));
        define('PDF_AUTHOR', SiteSetting::getStaticSiteSetting('site_name'));
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
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // $pdf->setFontSubsetting(true);
        $pdf->setFontSubsetting(false);
        // $pdf->SetFont('dejavusans', '', 11, '', true);
        $pdf->AddPage();
        // $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196, 196, 196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        return $pdf;
    }

    public static function createInvoice($pdf, $invoice, $invoiceitems, $contract)
    {
        $pdf->SetTitle('Factuur '.$invoice['factuurnummer']);
        $pdf->SetXY(165, 15);
        // $logo = $_SERVER["DOCUMENT_ROOT"].'/rental/invoices/beuklogo_1866x1866.png';
        $pdf->Image('logo_new_280px.jpg', '', '', 25, 25, '', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $pdf->SetXY(120, 60);
        $pdf->Multicell(0, 2, "Poppodium de Beuk\n1e Barendrechtseweg 53-55\n2992XE, Barendrecht\n\nKVK: 40341794\nIBAN: NL19RABO1017541353", $border = 0, $align = 'R');

        $pdf->SetXY(20, 70);
        $pdf->SetFont('dejavusans', 'B', 11, '', true);
        $pdf->Write(0, $contract['band_naam']."\n", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetFont('dejavusans', '', 11, '', true);
        $pdf->SetX(20);
        $pdf->Write(0, 'T.a.v. '.$contract['bandleider_naam']."\n", '', 0, 'L', true, 0, false, false, 0);
        // $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Write(0, $contract['bandleider_adres']."\n", '', 0, 'L', true, 0, false, false, 0);
        // $pdf->Ln();
        $pdf->SetX(20);
        $postcodewoonplaats = $contract['bandleider_postcode']." ".$contract['bandleider_woonplaats'];
        $pdf->Write(0, $postcodewoonplaats, '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetXY(20, 110);
        $pdf->SetFont('dejavusans', 'B', 11, '', true);
        $factuurtitel = 'Factuur';
        $pdf->Write(0, $factuurtitel, '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetXY(20, 120);
        $pdf->SetFont('dejavusans', '', 11, '', true);
        $factuurnummertekst = 'Factuurnummer: '.$invoice['factuurnummer'];
        $pdf->Write(0, $factuurnummertekst, '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetXY(120, 120);
        $newDate = date("d-m-Y", strtotime($invoice['factuurdatum']));
        $factuurtekstrechts = 'Factuurdatum: '.$newDate;
        // $factuurtekstrechts2 = 'Vervaldatum: '.$invoice['vervaldatum'];
        $pdf->Write(0, $factuurtekstrechts, '', 0, 'R', true, 0, false, false, 0);
        // $pdf->Ln();
        // $pdf->Write(0, $factuurtekstrechts2, '', 0, 'R', true, 0, false, false, 0);


        $pdf->SetXY(20, 140);
        $pdf->SetFont('dejavusans', 'B', 11, '', true);
        $pdf->Write(0, 'Omschrijving', '', 0, 'L', false, 0, false, false, 0);
        $pdf->SetX(150);
        $pdf->Write(0, 'Bedrag', '', 0, 'R', false, 0, false, false, 0);
        $pdf->Ln();
        $pdf->SetFont('dejavusans', '', 11, '', true);

        $totaalbedrag = 0;

        foreach ($invoiceitems as $invoiceitem) {
            $pdf->SetX(20);
            $pdf->Write(0, $invoiceitem['name'], '', 0, 'L', false, 0, false, false, 0);
            $pdf->SetX(165);
            $pdf->Write(0, '€', '', 0, 'L', false, 0, false, false, 0);

            $pdf->SetX(150);
            $pdf->Write(0, $invoiceitem['price'], '', 0, 'R', false, 0, false, false, 0);
            $pdf->Ln();
            $totaalbedrag = $totaalbedrag + $invoiceitem['price'];
        }
        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Write(0, 'Totaal:', '', 0, 'L', false, 0, false, false, 0);
        $pdf->SetX(165);
        $pdf->Write(0, '€', '', 0, 'L', false, 0, false, false, 0);
        $pdf->SetX(150);
        $pdf->Write(0, $totaalbedrag."\n\n\n", '', 0, 'R', false, 0, false, false, 0);

        $pdf->SetX(20);
        $gelieve  = 'Wij verzoeken u het bedrag binnen 14 dagen over te maken naar NL19 RABO 1017';
        $gelieve2 = '5413 53 o.v.v. het factuurnummer t.n.v. Sociëteit de Beuk.';

        $gelieve4 = 'Neem voor vragen over facturatie contact op met penningmeester@beukonline.nl.'."\n\n";
            $pdf->SetX(20);
        $pdf->Write(0, $gelieve, '', 0, '', true, 0, false, false, 0);
        $pdf->SetX(20);
        $pdf->Write(0, $gelieve2, '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetX(20);
        $pdf->Write(0, $gelieve4, '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetX(20);
        $pdf->Write(0, 'Met vriendelijke groet,'."\n", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetX(20);
        $pdf->Write(0, 'De penningmeester van Poppodium de Beuk.', '', 0, 'L', true, 0, false, false, 0);
        return $pdf;
    }

    public static function renderInvoice($invoice, $invoiceitems, $contract)
    {
        $pdf = self::configPDF();
        $pdf = self::createInvoice($pdf, $invoice, $invoiceitems, $contract);
        // ob_end_clean();
        $pdf->Output($invoice['factuurnummer'].'.pdf', 'I');
    }

    public static function writeInvoice($invoice, $invoiceitems, $contract)
    {
        $pdf = self::configPDF();
        $pdf = self::createInvoice($pdf, $invoice, $invoiceitems, $contract);

        if (file_exists($_SERVER["DOCUMENT_ROOT"].'content/invoices/'.$invoice['factuurnummer'].'.pdf')) {
            Session::add('feedback_negative', "Bestand bestaat al.");
            return false;
        }
        // ob_end_clean();
        $pdf->Output($_SERVER["DOCUMENT_ROOT"].'content/invoices/'.$invoice['factuurnummer'].'.pdf', 'F');
        if (file_exists($_SERVER["DOCUMENT_ROOT"].'content/invoices/'.$invoice['factuurnummer'].'.pdf')) {
            return true;
        }
        return false;
    }
}
