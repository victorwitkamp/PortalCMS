<?php

class InvoicePDF
{
    public static function render($invoice, $contract) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
        $pdf->setPrintHeader(FALSE);
        $pdf->setPrintFooter(FALSE);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Factuur '.$invoice['factuurnummer']);
        // $pdf->SetSubject('TCPDF Tutorial');
        // $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(TRUE);
        $pdf->SetFont('dejavusans', '', 11, '', TRUE);
        $pdf->AddPage();
        $pdf->setTextShadow(array('enabled'=>TRUE, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196, 196, 196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->SetXY(165, 15);

        $pdf->Image('logo.jpg', '', '', 25, 25, '', '', 'T', false, 300, '', false, false, 1, false, false, FALSE);
        $pdf->SetXY(120, 60);
        $afzender = '<p style="text-align:right">Poppodium de Beuk<br>
        1e Barendrechtseweg 53-55<br>
        2992XE, Barendrecht<br><br>
        KVK: 40341794<br>
        IBAN: NL19RABO1017541353<br><br></p>';
        $pdf->writeHTMLCell(0, 0, '', '', $afzender, 0, 1, 0, TRUE, '', TRUE);

        $pdf->SetXY(20, 70);
        $aan = '<p>'.$contract['bandleider_naam'].' ('.$contract['band_naam'].')<br>'
        .$contract['bandleider_adres'].'<br>'
        .$contract['bandleider_postcode'].' '.$contract['bandleider_woonplaats'].'</p>';
        $pdf->writeHTMLCell(0, 0, '', '', $aan, 0, 1, 0, TRUE, '', TRUE);

        $pdf->SetXY(20, 110);
        $factuurtitel = '<h1>Factuur</h1>';
        $pdf->writeHTMLCell(0, 0, '', '', $factuurtitel, 0, 1, 0, TRUE, '', TRUE);

        $pdf->SetXY(20, 120);
        $factuurtekst = '<p>Factuurnummer: '.$invoice['factuurnummer'].'</p>';
        $pdf->writeHTMLCell(0, 0, '', '', $factuurtekst, 0, 1, 0, TRUE, '', TRUE);

        $pdf->SetXY(120, 120);
        $factuurtekstrechts = '<p style="text-align:right">Factuurdatum: '.$invoice['factuurdatum'].'<br>Vervaldatum: '.$invoice['vervaldatum'].'</p>';
        $pdf->writeHTMLCell(0, 0, '', '', $factuurtekstrechts, 0, 1, 0, TRUE, '', TRUE);

        $pdf->SetXY(20, 140);
        $gelieve = '<p><i>Gelieve het verschuldigde bedrag binnen 14 dagen te storten op IBAN NL19 RABO 1017 5413 53 t.n.v. Sociëteit de Beuk te Barendrecht onder vermelding van het factuurnummer. Neem voor vragen over facturatie contact op met penningmeester@beukonline.nl.</i></p>';
        $pdf->writeHTMLCell(0, 0, '', '', $gelieve, 0, 1, 0, TRUE, '', TRUE);

        $pdf->SetXY(20, 170);
        $tblstart = '
        <table cellpadding="2" cellspacing="2" nobr="true">
            <tr>
                <th colspan="3"><strong>Omschrijving</strong></th>
                <th><strong>Bedrag</strong></th>
            </tr>';
        $tableend = '</table>';
        $invoiceitems = InvoiceItemMapper::getByInvoiceId($invoice['id']);
        $totaalbedrag = '0';
        $rows = '';
        foreach ($invoiceitems as $row) {
            $rows .= '
            <tr>
            <td colspan="3">'.$row['name'].'</td>
            <td>'.$row['price'].'</td>
            </tr>';
            $totaalbedrag = $totaalbedrag + $row['price'];
        }
        $tbl = $tblstart.$rows.$tableend;
        $pdf->writeHTML($tbl, TRUE, false, false, false, '');

        $totaallabel = '<p style="text-align:right"><strong>Totaal:<strong> &euro;'.$totaalbedrag;
        $pdf->writeHTML($totaallabel, TRUE, false, false, false, '');
        if ($pdf->Output($invoice['factuurnummer'].'.pdf', 'I')) {

        // if (file_exists($_SERVER["DOCUMENT_ROOT"].'content/invoices/'.$invoice['factuurnummer'].'.pdf')) {
        //     echo 'error - bestand bestaat al';
        //     return FALSE;
        // }
        // if ($pdf->Output($_SERVER["DOCUMENT_ROOT"].'content/invoices/'.$invoice['factuurnummer'].'.pdf', 'F')) {
            return TRUE;
            // Redirect::to("content/invoices/".$invoice['factuurnummer'].".pdf");

        }

        return FALSE;
    }
}