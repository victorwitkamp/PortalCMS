<?php

class Invoice
{
    public static function renderInvoiceById($Id = null)
    {
        if (empty($Id)) {
            return false;
        }
        if (!isset($Id)) {
            return false;
        }
        $invoice = self::getInvoiceById($Id);
        $contract = Contract::getById($invoice['contract_id']);
        $pdf = self::createPDF($invoice, $contract);
        if ($pdf) {
            return true;
        }
    }

    public static function addInvoiceItem()
    {
        $invoiceId = Request::post('invoiceid', true);
        $name = Request::post('name', true);
        $price = Request::post('price', true);
        $stmt = DB::conn()->prepare("SELECT id FROM invoice_items WHERE invoice_id = ? AND name = ? AND price = ?");
        $stmt->execute([$invoiceId, $name, $price]);
        if (!$stmt->rowCount() == 0) {
            Session::add('feedback_negative', "Factuuritem bestaat al");
        } else {
            if (!self::addInvoiceItemAction($invoiceId, $name, $price)) {
                Session::add('feedback_negative', "Toevoegen van factuuritem mislukt.");
            } else {
                Session::add('feedback_positive', "Factuuritem toegevoegd.");
                Redirect::redirectPage("rental/invoices/details.php?id=".$invoiceId);
            }
        }
    }

    public static function addInvoiceItemAction($invoiceId, $name, $price) {
        $stmt = DB::conn()->prepare("INSERT INTO invoice_items(id, invoice_id, name, price) VALUES (NULL,?,?,?)");
        $stmt->execute([$invoiceId, $name, $price]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function deleteInvoiceItem()
    {
        $Id = Request::post('id', true);
        $stmt = DB::conn()->prepare("SELECT * FROM invoice_items where id = ?");
        $stmt->execute([$Id]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $count = count($result);
        if ($count > 0) {
            if (!self::deleteInvoiceItemAction($Id)) {
                Session::add('feedback_negative', "Verwijderen van factuuritem mislukt.");
                return false;
            } else {
                Session::add('feedback_positive', "Factuuritem verwijderd.");
                return true;
            }
        } else {
            Session::add('feedback_negative', "Kan factuuritem niet verwijderen.<br>Factuuritem bestaat niet.");
            return false;
        }
    }

    public static function deleteInvoiceItemAction($Id) {
        $stmt = DB::conn()->prepare("DELETE FROM invoice_items WHERE id = ?");
        $stmt->execute([$Id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function new()
    {
        $contract_id = Request::post('contract_id', true);
        $year = Request::post('year', true);
        $month = Request::post('month', true);
        $contract = Contract::getById($contract_id);
        $factuurnummer = $year.$contract['bandcode'].$month;
        $factuurdatum = Request::post('factuurdatum', true);
        $vervaldatum = Request::post('vervaldatum', true);
        $stmt = DB::conn()->prepare("SELECT id FROM invoices WHERE factuurnummer = ?");
        $stmt->execute([$factuurnummer]);
        if (!$stmt->rowCount() == 0) {
            Session::add('feedback_negative', "Factuurnummer bestaat al.");
        } else {
            if (!self::addInvoiceAction($contract_id, $factuurnummer, $year, $month, $factuurdatum, $vervaldatum)) {
                Session::add('feedback_negative', "Toevoegen van factuur mislukt.");
            } else {
                $invoice = self::getInvoiceByFactuurnummer($factuurnummer);
                $factuuromschrijving_ruimte = 'Kosten voor: huur '.Text::get('MONTH_'.$month);
                self::addInvoiceItemAction($invoice['id'], $factuuromschrijving_ruimte, $contract['kosten_ruimte']);
                if (!empty($contract['huur_kast_nr'])) {
                    if ($contract['kosten_kast'] > 0) {
                        $factuuromschrijving_kast = 'Kosten voor: kast '.Text::get('MONTH_'.$month);
                        self::addInvoiceItemAction($invoice['id'], $factuuromschrijving_kast, $contract['kosten_kast']);
                    }
                }
                Session::add('feedback_positive', "Factuur toegevoegd.");
                Redirect::redirectPage("rental/invoices/");
            }
        }
    }

    public static function addInvoiceAction($contract_id, $factuurnummer, $year, $month, $factuurdatum, $vervaldatum)
    {
        $stmt = DB::conn()->prepare("INSERT INTO invoices(id, contract_id, factuurnummer, year, month, factuurdatum, vervaldatum) VALUES (NULL,?,?,?,?,?,?)");
        $stmt->execute([$contract_id, $factuurnummer, $year, $month, $factuurdatum, $vervaldatum]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function getInvoiceById($Id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoices WHERE id = ? limit 1");
        $stmt->execute([$Id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetch();
        }
    }

    public static function getInvoiceByFactuurnummer($factuurnummer)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoices WHERE factuurnummer = ? limit 1");
        $stmt->execute([$factuurnummer]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetch();
        }
    }

    public static function getInvoiceItemsById($id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoice_items where invoice_id = ?");
        $stmt->execute([$id]);
        if (!$stmt->rowCount() > 1) {
            return false;
        } else {
            return $stmt->fetchAll();
        }
    }

    public static function getAllInvoices()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoices");
        $stmt->execute();
        if (!$stmt->rowCount() > 0) {
            return false;
        } else {
            return $stmt->fetchAll();
        }
    }

    public static function displayInvoiceSumById($Id) {
        $sum = self::getInvoiceSumById($Id);
        if (!$sum) {
            return false;
        } else {
            return '&euro; '.$sum;
        }
    }

    public static function getInvoiceSumById($id)
    {
        $sum = 0;
        $invoiceitems = self::getInvoiceItemsById($id);
        foreach ($invoiceitems as $row) {
            $sum = $sum + $row['price'];
        }
        return $sum;
    }

    public static function getInvoicesByContractId($contractId)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoices where contract_id = ?");
        $stmt->execute([$contractId]);
        if (!$stmt->rowCount() > 0) {
            return false;
        } else {
            return $stmt->fetchAll();
        }
    }

    public static function createPDF($invoice, $contract) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Factuur '.$invoice['factuurnummer']);
        // $pdf->SetSubject('TCPDF Tutorial');
        // $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 11, '', true);
        $pdf->AddPage();
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196, 196, 196), 'opacity'=>1, 'blend_mode'=>'Normal'));
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
        $factuurtekst = '<p>Factuurnummer: '.$invoice['factuurnummer'].'</p>';
        $pdf->writeHTMLCell(0, 0, '', '', $factuurtekst, 0, 1, 0, true, '', true);

        $pdf->SetXY(120, 120);
        $factuurtekstrechts = '<p style="text-align:right">Factuurdatum: '.$invoice['factuurdatum'].'<br>Vervaldatum: '.$invoice['vervaldatum'].'</p>';
        $pdf->writeHTMLCell(0, 0, '', '', $factuurtekstrechts, 0, 1, 0, true, '', true);

        $pdf->SetXY(20, 140);
        $gelieve = '<p><i>Gelieve het verschuldigde bedrag binnen 14 dagen te storten op IBAN NL19 RABO 1017 5413 53 t.n.v. Sociëteit de Beuk te Barendrecht onder vermelding van het factuurnummer. Neem voor vragen over facturatie contact op met penningmeester@beukonline.nl.</i></p>';
        $pdf->writeHTMLCell(0, 0, '', '', $gelieve, 0, 1, 0, true, '', true);

        $pdf->SetXY(20, 170);
        $tblstart = '
        <table cellpadding="2" cellspacing="2" nobr="true">
            <tr>
                <th colspan="3"><strong>Omschrijving</strong></th>
                <th><strong>Bedrag</strong></th>
            </tr>';
        $tableend = '</table>';
        $invoiceitems = Invoice::getInvoiceItemsById($invoice['id']);
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
        $pdf->writeHTML($tbl, true, false, false, false, '');

        $totaallabel = '<p style="text-align:right"><strong>Totaal:<strong> &euro;'.$totaalbedrag;
        $pdf->writeHTML($totaallabel, true, false, false, false, '');
        if ($pdf->Output($invoice['factuurnummer'].'.pdf', 'I')) {

        // if (file_exists($_SERVER["DOCUMENT_ROOT"].'content/invoices/'.$invoice['factuurnummer'].'.pdf')) {
        //     echo 'error - bestand bestaat al';
        //     return false;
        // }
        // if ($pdf->Output($_SERVER["DOCUMENT_ROOT"].'content/invoices/'.$invoice['factuurnummer'].'.pdf', 'F')) {
            return true;
            // Redirect::redirectPage("content/invoices/".$invoice['factuurnummer'].".pdf");

        }

        return false;
    }

}
