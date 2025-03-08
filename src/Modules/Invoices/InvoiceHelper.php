<?php


declare(strict_types=1);

namespace App\Modules\Invoices;

use App\Core\Activity\Activity;
use App\Core\Email\Message\Attachment\EmailAttachmentMapper;
use App\Core\Email\Recipient\EmailRecipientMapper;
use App\Core\Email\Schedule\MailScheduleMapper;
use App\Core\Email\Template\EmailTemplateMapper;
use App\Core\Email\Template\Helpers\PlaceholderHelper;
use App\Core\Session\Session;
use App\Core\View\PDF;
use App\Core\View\Text;
use App\Modules\Contracts\ContractMapper;
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
                return true;
            }
            //todo $this->addFlash('danger','Nieuwe email aanmaken mislukt.');
        } else {
            //todo $this->addFlash('danger','Factuur niet gevonden.');
        }
        return false;
    }

    public static function create(int $year, string $month, array $contract_ids, string $factuurdatum): bool
    {
        if (empty($factuurdatum)) {
            //todo $this->addFlash('danger','Geen factuurdatum opgegeven.');
        } elseif (empty($year)) {
            //todo $this->addFlash('danger','Incompleet verzoek.');
        } elseif (empty($month)) {
            //todo $this->addFlash('danger','Incompleet verzoek.');
        } elseif (empty($contract_ids)) {
            //todo $this->addFlash('danger','Incompleet verzoek.');
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
            //todo $this->addFlash('danger','Toevoegen van factuur mislukt.');
        } else {
            //todo $this->addFlash('danger','Factuurnummer bestaat al.');
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
            //todo $this->addFlash('danger','Verwijderen van factuur mislukt. Factuur bestaat niet.');
        } elseif (!empty(InvoiceItemMapper::getByInvoiceId($id)) && !InvoiceItemMapper::deleteByInvoiceId($id)) {
            //todo $this->addFlash('danger','Verwijderen van factuur mislukt. Verwijderen van factuuritems voor factuur mislukt.');
        } elseif (($invoice->status > 0) && !unlink(DIR_ROOT . '/content/invoices/' . $invoice->factuurnummer . '.pdf')) {
            //todo $this->addFlash('danger','Verwijderen van factuur mislukt. PDF niet gevonden.');
        } elseif (InvoiceMapper::delete($id)) {
            //todo $this->addFlash('success','Factuur verwijderd.');
            Activity::add('NewInvoice', Session::get('user_id'), 'Factuurnr.: ' . $invoice->factuurnummer);
            return true;
        } else {
            //todo $this->addFlash('danger','Verwijderen van factuur mislukt.');
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
            }
        }
        return false;
    }

    public static function write(int $id = null): bool
    {
        if ($id !== null) {
            $invoice = InvoiceMapper::getById($id);
            if (!empty($invoice)) {
                $contract = ContractMapper::getById($invoice->contract_id);
                $invoiceitems = InvoiceItemMapper::getByInvoiceId($id);
                if (PDF::writeInvoice($invoice, $invoiceitems, $contract)) {
                    InvoiceMapper::updateStatus($id, 1);
                    return true;
                }
                //todo $this->addFlash('danger','Fout bij het opslaan.');
            }
            //todo $this->addFlash('danger','Fout bij het opslaan. Factuur niet gevonden.');
        } else {
            //todo $this->addFlash('danger','Fout bij het opslaan. Geen ID opgegeven.');
        }
        return false;
    }

    public static function createItem(int $invoiceId, string $name, int $price): bool
    {
        if (!InvoiceItemMapper::create($invoiceId, $name, $price)) {
            //todo $this->addFlash('danger','Toevoegen van factuuritem mislukt.');
            return false;
        }
        //todo $this->addFlash('success','Factuuritem toegevoegd.');
        Activity::add('AddInvoiceItem', Session::get('user_id'), 'Added item "' . $name . '" to invoice with ID = ' . $invoiceId);
        return true;
    }

    public static function deleteItem(int $id): bool
    {
        if (!InvoiceItemMapper::exists($id)) {
            //todo $this->addFlash('danger','Kan factuuritem niet verwijderen. Factuuritem bestaat niet.');
            return false;
        }
        if (!InvoiceItemMapper::delete($id)) {
            //todo $this->addFlash('danger','Verwijderen van factuuritem mislukt.');
            return false;
        }
        //todo $this->addFlash('success','Factuuritem verwijderd.');
        return true;
    }
}
