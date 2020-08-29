<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use League\Plates\Engine;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Modules\Invoices\InvoiceHelper;
use Psr\Http\Message\ResponseInterface;

/**
 * Class InvoicesController
 * @package PortalCMS\Controllers
 */
class InvoicesController
{
    protected $templates;

//    private $requests = [
//        'createInvoiceMail'  => 'POST',
//        'writeInvoice'       => 'POST',
//        'createInvoice'      => 'POST',
//        'deleteInvoice'      => 'POST',
//        'deleteInvoiceItem'  => 'POST',
//        'addInvoiceItem'     => 'POST',
//        'showInvoicesByYear' => 'POST'
//    ];

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public static function createInvoiceMail() : ResponseInterface
    {
        $invoiceIds = Request::post('id');
        if (!empty($invoiceIds)) {
            MailBatch::create();
            $batchId = MailBatch::lastInsertedId();
            Session::add('feedback_positive', 'Nieuwe batch aangemaakt (batch ID: ' . $batchId . '). <a href="email/Messages?batch_id=' . $batchId . '">Batch bekijken</a>');
            foreach ($invoiceIds as $invoiceId) {
                InvoiceHelper::createMail((int) $invoiceId, (int) $batchId);
            }
            return new RedirectResponse('/Invoices');
        }
        return new RedirectResponse('/Error/Error');
    }

    public static function writeInvoice() : ResponseInterface
    {
        $ids = Request::post('writeInvoiceId');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                InvoiceHelper::write((int) $id);
            }
            return new RedirectResponse('/Invoices');
        }
        return new RedirectResponse('/Error/Error');
    }

    public static function createInvoice() : ResponseInterface
    {
        $year = (int) Request::post('year', true);
        $month = (string) Request::post('month', true);
        $contracts = (array) Request::post('contract_id');
        $factuurdatum = (string) Request::post('factuurdatum', true);
        if (InvoiceHelper::create($year, $month, $contracts, $factuurdatum)) {
            Session::add('feedback_positive', 'Factuur toegevoegd.');
            return new RedirectResponse('/Invoices');
        }
    }

    public static function deleteInvoice() : ResponseInterface
    {
        if (InvoiceHelper::delete((int) Request::post('id', true))) {
            return new RedirectResponse('/Invoices');
        }
        return new RedirectResponse('/Error/Error');
    }

    public static function deleteInvoiceItem() : ResponseInterface
    {
        if (InvoiceHelper::deleteItem((int) Request::post('id', true))) {
            return new RedirectResponse('/Invoices/Details?id=' . (int) Request::post('invoiceid', true));
        }
        return new RedirectResponse('/Error/Error');
    }

    public static function addInvoiceItem() : ResponseInterface
    {
        $invoiceId = (int) Request::post('invoiceid', true);
        if (InvoiceHelper::createItem($invoiceId, (string) Request::post('name', true), (int) Request::post('price', true))) {
            return new RedirectResponse('/Invoices/Details?id=' . $invoiceId);
        }
        return new RedirectResponse('/Error/Error');
    }

    public static function showInvoicesByYear() : ResponseInterface
    {
        return new RedirectResponse('/Invoices?Year=' . Request::post('year'));
    }

    public function index() : ResponseInterface
    {
        if (Authorization::hasPermission('rental-invoices')) {
            return new HtmlResponse($this->templates->render('Pages/Invoices/Index'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function add() : ResponseInterface
    {
        if (Authorization::hasPermission('rental-invoices')) {
            return new HtmlResponse($this->templates->render('Pages/Invoices/Add'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function details() : ResponseInterface
    {
        if (Authorization::hasPermission('rental-invoices')) {
            return new HtmlResponse($this->templates->render('Pages/Invoices/Details'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function createPDF() : ResponseInterface
    {
        if (Authorization::hasPermission('rental-invoices')) {
            return new HtmlResponse($this->templates->render('Pages/Invoices/CreatePDF'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }
}
