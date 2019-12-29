<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

use function is_array;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;
use PortalCMS\Core\Email\Recipient\EmailRecipientMapper;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\Email\Template\EmailTemplatePDOReader;
use PortalCMS\Core\Email\Template\Helpers\PlaceholderHelper;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\PDF;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Modules\Invoices\InvoiceItemMapper;
use PortalCMS\Modules\Invoices\InvoiceMapper;

class InvoiceHelper
{
    public static function createMail(int $invoiceId, int $batchId = null): bool
    {
        $invoice = InvoiceMapper::getById($invoiceId);
        if (empty($invoice)) {
            return false;
        }
        $contract = ContractMapper::getById($invoice->contract_id);
        if ((int) $invoice->month < 10) {
            $maand = Text::get('MONTH_0' . $invoice->month);
        } else {
            $maand = Text::get('MONTH_' . $invoice->month);
        }
        $template = EmailTemplatePDOReader::getSystemTemplateByName('InvoiceMail');
        $subject = PlaceholderHelper::replace('MAAND', $maand, $template['subject']);
        $body = PlaceholderHelper::replace('FACTUURNUMMER', $invoice->factuurnummer, $template['body']);
        $create = MailScheduleMapper::create($batchId, null, $subject, $body);
        if (!$create) {
            Session::add('feedback_negative', 'Nieuwe email aanmaken mislukt.');
            return false;
        }
        $createdMailId = MailScheduleMapper::lastInsertedId();
        EmailRecipientMapper::createRecipient($createdMailId, $contract->bandleider_email);
        EmailAttachmentMapper::create($createdMailId, 'content/invoices/', $invoice->factuurnummer, '.pdf');
        InvoiceMapper::updateMailId($invoiceId, $createdMailId);
        InvoiceMapper::updateStatus($invoiceId, 2);
        return true;
    }

    public static function createInvoiceAction(string $year, string $month, int $contract_id, $factuurdatum): bool
    {
        $contract = ContractMapper::getById($contract_id);
        $factuurnummer = $year . $contract->bandcode . $month;
        if (!empty(InvoiceMapper::getByFactuurnummer($factuurnummer))) {
            Session::add('feedback_negative', 'Factuurnummer bestaat al.');
            return false;
        }
        if (!InvoiceMapper::create($contract_id, $factuurnummer, $year, $month, $factuurdatum)) {
            Session::add('feedback_negative', 'Toevoegen van factuur mislukt.');
            return false;
        }
        $invoice = InvoiceMapper::getByFactuurnummer($factuurnummer);
        $kosten_ruimte = (int) $contract->kosten_ruimte;
        $kosten_kast = (int) $contract->kosten_kast;
        if (($kosten_ruimte > 0) && !InvoiceItemMapper::create($invoice->id, 'Huur oefenruimte - ' . Text::get('MONTH_' . $month), $kosten_ruimte)) {
            return false;
        }
        if (($kosten_kast > 0) && !InvoiceItemMapper::create($invoice->id, 'Huur kast - ' . Text::get('MONTH_' . $month), $kosten_kast)) {
            return false;
        }
        return true;
    }

    public static function create(string $year, string $month, array $contract_ids, $factuurdatum): bool
    {
        if (empty($factuurdatum)) {
            Session::add('feedback_negative', 'Geen factuurdatum opgegeven.');
        } elseif (empty($year)) {
            Session::add('feedback_negative', 'Incompleet verzoek.');
        } elseif (empty($month)) {
            Session::add('feedback_negative', 'Incompleet verzoek.');
        } elseif (empty($contract_ids)) {
            Session::add('feedback_negative', 'Incompleet verzoek.');
        } else {
            foreach ($contract_ids as $contract_id) {
                if (!self::createInvoiceAction($year, $month, (int) $contract_id, $factuurdatum)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public static function displayInvoiceSumById(int $id)
    {
        $sum = self::getInvoiceSumById($id);
        if (!$sum) {
            return false;
        }
        return '&euro; ' . $sum;
    }

    public static function getInvoiceSumById(int $id): int
    {
        $sum = 0;
        $items = InvoiceItemMapper::getByInvoiceId($id);
        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                $sum += $item->price;
            }
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
        if (!empty(InvoiceItemMapper::getByInvoiceId($id)) && !InvoiceItemMapper::deleteByInvoiceId($id)) {
            Session::add('feedback_negative', 'Verwijderen van factuuritems voor factuur mislukt.');
            return false;
        }
        if (($invoice->status > 0) && !unlink(DIR_ROOT . '/content/invoices/' . $invoice->factuurnummer . '.pdf')) {
            Session::add('feedback_negative', 'PDF niet gevonden.');
            return false;
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

    public static function createItem($invoiceId, $name, $price): bool
    {
        if (!InvoiceItemMapper::create($invoiceId, $name, $price)) {
            Session::add('feedback_negative', 'Toevoegen van factuuritem mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Factuuritem toegevoegd.');
        return true;
    }

    public static function deleteItem(int $id): bool
    {
        if (!InvoiceItemMapper::exists($id)) {
            Session::add('feedback_negative', 'Kan factuuritem niet verwijderen. Factuuritem bestaat niet.');
            return false;
        }
        if (!InvoiceItemMapper::delete($id)) {
            Session::add('feedback_negative', 'Verwijderen van factuuritem mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Factuuritem verwijderd.');
        return true;
    }
}