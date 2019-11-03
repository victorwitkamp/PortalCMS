<?php

namespace PortalCMS\Modules\Invoices;

use PortalCMS\Core\PDF\PDF;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Email\Template\EmailTemplate;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\Email\Template\EmailTemplatePDOReader;
use PortalCMS\Core\Email\Recipient\EmailRecipientMapper;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;

class InvoiceModel
{
    public static function createMail()
    {
        $invoiceId = Request::post('id', true);
        $invoice = InvoiceMapper::getById($invoiceId);
        $contract = ContractMapper::getById($invoice['contract_id']);
        if ($invoice['month'] < '10') {
            $maand = Text::get('MONTH_0' . $invoice['month']);
        } else {
            $maand = Text::get('MONTH_' . $invoice['month']);
        }
        $template = EmailTemplatePDOReader::getSystemTemplateByName('InvoiceMail');
        $subject = EmailTemplate::replaceholder('MAAND', $maand, $template['subject']);
        $body = EmailTemplate::replaceholder('FACTUURNUMMER', $invoice['factuurnummer'], $template['body']);
        $create = MailScheduleMapper::create(null, null, $subject, $body);
        if (!$create) {
            Session::add('feedback_negative', 'Nieuwe email aanmaken mislukt.');
            return false;
        }
        $createdMailId = MailScheduleMapper::lastInsertedId();
        // $recipients = array($recipient_email);
        // foreach ($recipients as $recipient) {
        EmailRecipientMapper::createRecipient($createdMailId, $contract['bandleider_email']);
        // }

        $attachmentPath = 'content/invoices/';
        $attachmentExtension = '.pdf';
        $attachmentName = $invoice['factuurnummer'];
        EmailAttachmentMapper::create($createdMailId, $attachmentPath, $attachmentName, $attachmentExtension);

        InvoiceMapper::updateMailId($invoiceId, $createdMailId);
        InvoiceMapper::updateStatus($invoiceId, 2);
        Session::add('feedback_positive', 'Email toegevoegd (ID = ' . $createdMailId . ')');
        return true;
    }

    public static function getByContractId($contract_id)
    {
        $Invoices = InvoiceMapper::getByContractId($contract_id);
        if (!$Invoices) {
            return false;
        }
        return $Invoices;
    }

    public static function create($year, $month, $contract_ids)
    {
        if (empty($contract_ids)) {
            return false;
        }
        foreach ($contract_ids as $contract_id) {
            $contract = ContractMapper::getById($contract_id);
            $factuurnummer = $year . $contract['bandcode'] . $month;
            $factuurdatum = Request::post('factuurdatum', true);
            if (InvoiceMapper::getByFactuurnummer($factuurnummer)) {
                Session::add('feedback_negative', 'Factuurnummer bestaat al.');
                return false;
            }
            if (!InvoiceMapper::create($contract_id, $factuurnummer, $year, $month, $factuurdatum)) {
                Session::add('feedback_negative', 'Toevoegen van factuur mislukt.');
                return false;
            }
            $invoice = InvoiceMapper::getByFactuurnummer($factuurnummer);
            if ($contract['kosten_ruimte'] > 0) {
                InvoiceItemMapper::create($invoice['id'], 'Huur oefenruimte - ' . Text::get('MONTH_' . $month), $contract['kosten_ruimte']);
            }
            if ($contract['kosten_kast'] > 0) {
                InvoiceItemMapper::create($invoice['id'], 'Huur kast - ' . Text::get('MONTH_' . $month), $contract['kosten_kast']);
            }
        }
        Session::add('feedback_positive', 'Factuur toegevoegd.');
        return true;
    }

    public static function displayInvoiceSumById(int $id)
    {
        $sum = self::getInvoiceSumById($id);
        if (!$sum) {
            return false;
        }
        return '&euro; ' . $sum;
    }

    public static function getInvoiceSumById(int $id)
    {
        $sum = 0;
        foreach (InvoiceItemMapper::getByInvoiceId($id) as $row) {
            $sum += $row['price'];
        }
        return $sum;
    }

    public static function delete(int $id)
    {
        $invoice = InvoiceMapper::getById($id);
        if (!$invoice) {
            Session::add('feedback_negative', 'Kan factuur niet verwijderen. Factuur bestaat niet.');
            return false;
        }
        if (InvoiceItemMapper::getByInvoiceId($id)) {
            if (!InvoiceItemMapper::deleteByInvoiceId($id)) {
                Session::add('feedback_negative', 'Verwijderen van factuuritems voor factuur mislukt.');
                return false;
            }
        }
        if ($invoice['status'] > 0) {
            unlink(DIR_ROOT . 'content/invoices/' . $invoice['factuurnummer'] . '.pdf');
        }
        if (!InvoiceMapper::delete($id)) {
            Session::add('feedback_negative', 'Verwijderen van factuur mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Factuur verwijderd.');
        return true;
    }

    public static function render(int $id)
    {
        if (empty($id)) {
            return false;
        }
        $invoice = InvoiceMapper::getById($id);
        $invoiceitems = InvoiceItemMapper::getByInvoiceId($id);
        $contract = ContractMapper::getById($invoice['contract_id']);
        PDF::renderInvoice($invoice, $invoiceitems, $contract);
    }

    public static function write($id = null)
    {
        if (empty($id)) {
            return false;
        }
        $invoice = InvoiceMapper::getById($id);
        $contract = ContractMapper::getById($invoice['contract_id']);
        $invoiceitems = InvoiceItemMapper::getByInvoiceId($id);
        if (!PDF::writeInvoice($invoice, $invoiceitems, $contract)) {
            Session::add('feedback_negative', 'Fout bij het opslaan');
            return false;
        }
        InvoiceMapper::updateStatus($id, 1);
        return true;
    }
}
