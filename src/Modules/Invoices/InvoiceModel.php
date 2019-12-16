<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;
use PortalCMS\Core\Email\Recipient\EmailRecipientMapper;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\Email\Template\EmailTemplatePDOReader;
use PortalCMS\Core\Email\Template\Helpers\PlaceholderHelper;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\PDF;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Contracts\ContractMapper;

class InvoiceModel
{
    public static function createMail($invoiceId, $batchId = null): bool
    {
        $invoice = InvoiceMapper::getById($invoiceId);
        if (empty($invoice)) {
            return false;
        }
        $contract = ContractMapper::getById($invoice->contract_id);
        if ($invoice->month < '10') {
            $maand = Text::get('MONTH_0' . $invoice->month);
        } else {
            $maand = Text::get('MONTH_' . $invoice->month);
        }
        $template = EmailTemplatePDOReader::getSystemTemplateByName('InvoiceMail');
        $subject = PlaceholderHelper::replaceholder('MAAND', $maand, $template['subject']);
        $body = PlaceholderHelper::replaceholder('FACTUURNUMMER', $invoice->factuurnummer, $template['body']);
        $create = MailScheduleMapper::create($batchId, null, $subject, $body);
        if (!$create) {
            Session::add('feedback_negative', 'Nieuwe email aanmaken mislukt.');
            return false;
        }
        $createdMailId = MailScheduleMapper::lastInsertedId();
        EmailRecipientMapper::createRecipient($createdMailId, $contract->bandleider_email);

        $attachmentPath = 'content/invoices/';
        $attachmentExtension = '.pdf';
        $attachmentName = $invoice->factuurnummer;
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
            return null;
        }
        return $Invoices;
    }

    public static function createInvoiceAction($year, $month, $contract_id)
    {
        $contract = ContractMapper::getById((int) $contract_id);
        $factuurnummer = $year . $contract->bandcode . $month;
        $factuurdatum = Request::post('factuurdatum', true);
        if (!empty(InvoiceMapper::getByFactuurnummer($factuurnummer))) {
            Session::add('feedback_negative', 'Factuurnummer bestaat al.');
            return false;
        }
        if (!InvoiceMapper::create($contract_id, $factuurnummer, $year, $month, $factuurdatum)) {
            Session::add('feedback_negative', 'Toevoegen van factuur mislukt.');
            return false;
        }
        $invoice = InvoiceMapper::getByFactuurnummer($factuurnummer);
        if ($contract->kosten_ruimte > 0) {
            InvoiceItemMapper::create($invoice->id, 'Huur oefenruimte - ' . Text::get('MONTH_' . $month), $contract->kosten_ruimte);
        }
        if ($contract->kosten_kast > 0) {
            InvoiceItemMapper::create($invoice->id, 'Huur kast - ' . Text::get('MONTH_' . $month), $contract->kosten_kast);
        }
    }

    public static function create($year, $month, $contract_ids): bool
    {
        if (empty($contract_ids)) {
            return false;
        }
        if (is_array($contract_ids)) {
            foreach ($contract_ids as $contract_id) {
                self::createInvoiceAction($year, $month, $contract_id);
            }
        } else {
            self::createInvoiceAction($year, $month, $contract_ids);
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
            $sum += $row->price;
        }
        return $sum;
    }

    public static function delete(int $id): bool
    {
        $invoice = InvoiceMapper::getById($id);
        if (empty($invoice)) {
            Session::add('feedback_negative', 'Kan factuur niet verwijderen. Factuur bestaat niet.');
            return false;
        }
        if (!empty(InvoiceItemMapper::getByInvoiceId($id))) {
            if (!InvoiceItemMapper::deleteByInvoiceId($id)) {
                Session::add('feedback_negative', 'Verwijderen van factuuritems voor factuur mislukt.');
                return false;
            }
        }
        if ($invoice->status > 0) {
            unlink(DIR_ROOT . 'content/invoices/' . $invoice->factuurnummer . '.pdf');
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
        if (empty($invoice)) {
            return false;
        }
        $invoiceitems = InvoiceItemMapper::getByInvoiceId($id);
        $contract = ContractMapper::getById($invoice->contract_id);
        return PDF::renderInvoice($invoice, $invoiceitems, $contract);
    }

    public static function write(int $id = null): bool
    {
        if (empty($id)) {
            return false;
        }
        $invoice = InvoiceMapper::getById($id);
        if (empty($invoice)) {
            return false;
        }
        $contract = ContractMapper::getById($invoice->contract_id);
        $invoiceitems = InvoiceItemMapper::getByInvoiceId($id);
        if (!PDF::writeInvoice($invoice, $invoiceitems, $contract)) {
            Session::add('feedback_negative', 'Fout bij het opslaan');
            return false;
        }
        InvoiceMapper::updateStatus($id, 1);
        return true;
    }
}
