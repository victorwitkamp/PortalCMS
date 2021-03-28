<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

use PortalCMS\Core\Activity\Activity;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;
use PortalCMS\Core\Email\Recipient\EmailRecipientMapper;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\Email\Template\EmailTemplateMapper;
use PortalCMS\Core\Email\Template\Helpers\PlaceholderHelper;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\PDF;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Contracts\ContractMapper;
use function is_array;

class InvoiceHelper
{
    public static function createMail(int $invoiceId, int $batchId = null): bool
    {
        $invoice = InvoiceMapper::getById($invoiceId);
        if (!empty($invoice)) {
            $template = EmailTemplateMapper::getSystemTemplateByName('InvoiceMail');
            $subject = PlaceholderHelper::replace('MAAND', Text::get('MONTH_' . (((int)$invoice->month < 10) ? '0' : '') . $invoice->month), $template['subject']);
            $body = PlaceholderHelper::replace('FACTUURNUMMER', $invoice->factuurnummer, $template['body']);
            if (MailScheduleMapper::create($batchId, null, $subject, $body)) {
                $createdMailId = MailScheduleMapper::lastInsertedId();
                $contract = ContractMapper::getById($invoice->contract_id);
                EmailRecipientMapper::createRecipient($createdMailId, $contract->bandleider_email);
                EmailAttachmentMapper::create($createdMailId, 'content/invoices/', $invoice->factuurnummer, '.pdf');
                InvoiceMapper::updateMailId($invoiceId, $createdMailId);
                InvoiceMapper::updateStatus($invoiceId, 2);
                return true;
            }
            Session::add('feedback_negative', 'Nieuwe email aanmaken mislukt.');
        } else {
            Session::add('feedback_negative', 'Factuur niet gevonden.');
        }
        return false;
    }

    public static function create(int $year, string $month, array $contract_ids, string $factuurdatum): bool
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
                if (!self::createInvoiceAction($year, $month, (int)$contract_id, $factuurdatum)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public static function createInvoiceAction(int $year, string $month, int $contract_id, string $factuurdatum): bool
    {
        $contract = ContractMapper::getById($contract_id);
        $factuurnummer = $year . $contract->bandcode . $month;
        if (empty(InvoiceMapper::getByFactuurnummer($factuurnummer))) {
            if (InvoiceMapper::create($contract_id, $factuurnummer, $year, (int)$month, $factuurdatum)) {
                $invoice = InvoiceMapper::getByFactuurnummer($factuurnummer);
                $kosten_ruimte = (int)$contract->kosten_ruimte;
                $kosten_kast = (int)$contract->kosten_kast;
                if (($kosten_ruimte > 0)) {
                    InvoiceItemMapper::create($invoice->id, 'Huur oefenruimte - ' . Text::get('MONTH_' . $month), $kosten_ruimte);
                }
                if (($kosten_kast > 0)) {
                    InvoiceItemMapper::create($invoice->id, 'Huur kast - ' . Text::get('MONTH_' . $month), $kosten_kast);
                }
                Activity::add('NewInvoice', Session::get('user_id'), 'Factuurnr.: ' . $factuurnummer);
                return true;
            }
            Session::add('feedback_negative', 'Toevoegen van factuur mislukt.');
        } else {
            Session::add('feedback_negative', 'Factuurnummer bestaat al.');
        }
        return false;
    }

    public static function displayInvoiceSumById(int $id)
    {
//        $sum = self::getInvoiceSumById($id);
//        if (!$sum) {
//            return false;
//        }
//        return '&euro; ' . $sum;
        return self::getInvoiceSumById($id);
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
            Session::add('feedback_negative', 'Verwijderen van factuur mislukt. Factuur bestaat niet.');
        } elseif (!empty(InvoiceItemMapper::getByInvoiceId($id)) && !InvoiceItemMapper::deleteByInvoiceId($id)) {
            Session::add('feedback_negative', 'Verwijderen van factuur mislukt. Verwijderen van factuuritems voor factuur mislukt.');
        } elseif (($invoice->status > 0) && !unlink(DIR_ROOT . '/content/invoices/' . $invoice->factuurnummer . '.pdf')) {
            Session::add('feedback_negative', 'Verwijderen van factuur mislukt. PDF niet gevonden.');
        } elseif (InvoiceMapper::delete($id)) {
            Session::add('feedback_positive', 'Factuur verwijderd.');
            Activity::add('NewInvoice', Session::get('user_id'), 'Factuurnr.: ' . $invoice->factuurnummer);
            return true;
        } else {
            Session::add('feedback_negative', 'Verwijderen van factuur mislukt.');
        }
        return false;
    }

    public static function render(int $id)
    {
        if (!empty($id)) {
            $invoice = InvoiceMapper::getById($id);
            if (!empty($invoice)) {
                $invoiceitems = InvoiceItemMapper::getByInvoiceId($id);
                $contract = ContractMapper::getById($invoice->contract_id);
                return PDF::renderInvoice($invoice, $invoiceitems, $contract);
            } else {
                echo 'invoice empty';
                die;
            }
        }
        return false;
    }

    public static function write(int $id = null): bool
    {
        if (!empty($id)) {
            $invoice = InvoiceMapper::getById($id);
            if (!empty($invoice)) {
                $contract = ContractMapper::getById($invoice->contract_id);
                $invoiceitems = InvoiceItemMapper::getByInvoiceId($id);
                if (PDF::writeInvoice($invoice, $invoiceitems, $contract)) {
                    InvoiceMapper::updateStatus($id, 1);
                    return true;
                }
                Session::add('feedback_negative', 'Fout bij het opslaan.');
            }
            Session::add('feedback_negative', 'Fout bij het opslaan. Factuur niet gevonden.');
        } else {
            Session::add('feedback_negative', 'Fout bij het opslaan. Geen ID opgegeven.');
        }
        return false;
    }

    public static function createItem(int $invoiceId, string $name, int $price): bool
    {
        if (!InvoiceItemMapper::create($invoiceId, $name, $price)) {
            Session::add('feedback_negative', 'Toevoegen van factuuritem mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Factuuritem toegevoegd.');
        Activity::add('AddInvoiceItem', Session::get('user_id'), 'Added item "' . $name . '" to invoice with ID = ' . $invoiceId);
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
