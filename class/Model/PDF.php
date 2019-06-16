<?php

class PDF
{
    public static function configPDF() {
        define('K_TCPDF_EXTERNAL_CONFIG', true);

        /**
         * Installation path (/var/www/tcpdf/).
         * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
         */
        define('K_PATH_MAIN', $_SERVER["DOCUMENT_ROOT"].'/vendor/tecnickcom/tcpdf/');

        /**
         * URL path to tcpdf installation folder (http://localhost/tcpdf/).
         * By default it is automatically set but you can also set it as a fixed string to improve performances.
         */
        define('K_PATH_URL', Config::get('URL').'vendor/tecnickcom/tcpdf/');

        /**
         * Path for PDF fonts.
         * By default it is automatically set but you can also set it as a fixed string to improve performances.
         */
        define('K_PATH_FONTS', K_PATH_MAIN.'fonts/');

        /**
         * Default images directory.
         * By default it is automatically set but you can also set it as a fixed string to improve performances.
         */
        define('K_PATH_IMAGES', dirname(__FILE__).'/../images/');


        define('PDF_HEADER_LOGO', 'logo.jpg');
        define('PDF_HEADER_LOGO_WIDTH', 30);

        /**
         * Cache directory for temporary files (full path).
         */
        define('K_PATH_CACHE', sys_get_temp_dir().'/');

        /**
         * Generic name for a blank image.
         */
        define('K_BLANK_IMAGE', '_blank.png');

        /**
         * Page format.
         */
        define('PDF_PAGE_FORMAT', 'A4');

        /**
         * Page orientation (P=portrait, L=landscape).
         */
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

        /**
         * Set to true to enable the special procedure used to avoid the overlappind of symbols on Thai language.
         */
        define('K_THAI_TOPCHARS', true);

        /**
         * If true allows to call TCPDF methods using HTML syntax
         * IMPORTANT: For security reason, disable this feature if you are printing user HTML content.
         */
        define('K_TCPDF_CALLS_IN_HTML', true);

        /**
         * If true and PHP version is greater than 5, then the Error() method throw new exception instead of terminating the execution.
         */
        define('K_TCPDF_THROW_EXCEPTION_ERROR', false);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 11, '', true);
        $pdf->AddPage();
        // $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196, 196, 196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        return $pdf;
    }

    public static function createInvoice($pdf, $invoice, $invoiceitems, $contract) {
        $pdf->SetTitle('Factuur '.$invoice['factuurnummer']);
        $pdf->SetXY(165, 15);
        $pdf->Image('logo.jpg', '', '', 25, 25, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
        $pdf->SetXY(120, 60);
        $afzender = '<p style="text-align:right">Poppodium de Beuk<br>
        1e Barendrechtseweg 53-55<br>
        2992XE, Barendrecht<br><br>
        KVK: 40341794<br>
        IBAN: NL19RABO1017541353<br><br></p>';
        $pdf->writeHTMLCell(0, 0, '', '', $afzender, 0, 1, 0, true, '', true);

        $pdf->SetXY(20, 70);
        $aan = '<p>'.$contract['bandleider_naam'].' ('.$contract['band_naam'].')<br>'
        .$contract['bandleider_adres'].'<br>'
        .$contract['bandleider_postcode'].' '.$contract['bandleider_woonplaats'].'</p>';
        $pdf->writeHTMLCell(0, 0, '', '', $aan, 0, 1, 0, true, '', true);

        $pdf->SetXY(20, 110);
        $factuurtitel = '<h1>Factuur</h1>';
        $pdf->writeHTMLCell(0, 0, '', '', $factuurtitel, 0, 1, 0, true, '', true);

        $pdf->SetXY(20, 120);
        $factuurnummertekst = '<p>Factuurnummer: '.$invoice['factuurnummer'].'</p>';
        $pdf->writeHTMLCell(0, 0, '', '', $factuurnummertekst, 0, 1, 0, true, '', true);

        $pdf->SetXY(120, 120);
        $factuurtekstrechts = '<p style="text-align:right">Factuurdatum: '.$invoice['factuurdatum'].'<br>Vervaldatum: '.$invoice['vervaldatum'].'</p>';
        $pdf->writeHTMLCell(0, 0, '', '', $factuurtekstrechts, 0, 1, 0, true, '', true);

        $pdf->SetXY(20, 140);


        $totaalbedrag = 0;
        $tblcontent = '';
        foreach ($invoiceitems as $invoiceitem) {
            $tblcontent .= '
            <tr>
            <td colspan="3">'.$invoiceitem['name'].'</td>
            <td>'.$invoiceitem['price'].'</td>
            </tr>';
            $totaalbedrag = $totaalbedrag + $invoiceitem['price'];
        }
        $tbl =
        '<table cellpadding="2" cellspacing="2" nobr="true">
        <tr>
            <th colspan="3"><strong>Omschrijving</strong></th>
            <th><strong>Bedrag</strong></th>
        </tr>' . $tblcontent . '</table>';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        $totaal = '<p style="text-align:right"><strong>Totaal:<strong> &euro;'.$totaalbedrag.'</p>';
        $pdf->writeHTML($totaal, true, false, false, false, '');

        // $pdf->SetXY(20, 140);
        $gelieve = '<p><i>Gelieve het verschuldigde bedrag binnen 14 dagen te storten op IBAN NL19 RABO 1017 5413 53 t.n.v. Sociëteit de Beuk te Barendrecht onder vermelding van het factuurnummer. Neem voor vragen over facturatie contact op met penningmeester@beukonline.nl.</i></p>';
        // $pdf->writeHTMLCell(0, 0, '', '', $gelieve, 0, 1, 0, true, '', true);
        $pdf->writeHTML($gelieve, true, false, false, false, '');
        return $pdf;
    }

    public static function renderInvoice($invoice, $invoiceitems, $contract)
    {
        $pdf = self::configPDF();
        $pdf = self::createInvoice($pdf, $invoice, $invoiceitems, $contract);
        if ($pdf->Output($invoice['factuurnummer'].'.pdf', 'I')) {
            return true;
        }
        return false;
    }

    public static function writeInvoice($invoice, $invoiceitems, $contract)
    {
        $pdf = self::configPDF();
        $pdf = self::createInvoice($pdf, $invoice, $invoiceitems, $contract);

        if (file_exists($_SERVER["DOCUMENT_ROOT"].'content/invoices/'.$invoice['factuurnummer'].'.pdf')) {
            Session::add('feedback_negative', "Bestand bestaat al.");
            return false;
        }
        $pdf->Output($_SERVER["DOCUMENT_ROOT"].'content/invoices/'.$invoice['factuurnummer'].'.pdf', 'F');
        return true;
    }
}