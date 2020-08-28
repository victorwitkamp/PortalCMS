<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Modules\Invoices\InvoiceHelper;

/**
 * Class InvoicesController
 * @package PortalCMS\Controllers
 */
class InvoicesController extends Controller
{
    private $requests = [
        'createInvoiceMail' => 'POST', 'writeInvoice' => 'POST', 'createInvoice' => 'POST', 'deleteInvoice' => 'POST', 'deleteInvoiceItem' => 'POST', 'addInvoiceItem' => 'POST', 'showInvoicesByYear' => 'POST'
    ];

    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    public static function createInvoiceMail()
    {
        $invoiceIds = Request::post('id');
        if (!empty($invoiceIds)) {
            MailBatch::create();
            $batchId = MailBatch::lastInsertedId();
            Session::add('feedback_positive', 'Nieuwe batch aangemaakt (batch ID: ' . $batchId . '). <a href="email/Messages?batch_id=' . $batchId . '">Batch bekijken</a>');
            foreach ($invoiceIds as $invoiceId) {
                InvoiceHelper::createMail((int)$invoiceId, (int)$batchId);
            }
            Redirect::to('Invoices');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function writeInvoice()
    {
        $ids = Request::post('writeInvoiceId');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                InvoiceHelper::write((int)$id);
            }
            Redirect::to('Invoices');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function createInvoice()
    {
        $year = (int)Request::post('year', true);
        $month = (string)Request::post('month', true);
        $contracts = (array)Request::post('contract_id');
        $factuurdatum = (string)Request::post('factuurdatum', true);
        if (InvoiceHelper::create($year, $month, $contracts, $factuurdatum)) {
            Session::add('feedback_positive', 'Factuur toegevoegd.');
            Redirect::to('Invoices/');
        }
    }

    public static function deleteInvoice()
    {
        if (InvoiceHelper::delete((int)Request::post('id', true))) {
            Redirect::to('Invoices/Index');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function deleteInvoiceItem()
    {
        if (InvoiceHelper::deleteItem((int)Request::post('id', true))) {
            Redirect::to('Invoices/Details?id=' . (int)Request::post('invoiceid', true));
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function addInvoiceItem()
    {
        $invoiceId = (int)Request::post('invoiceid', true);
        if (InvoiceHelper::createItem($invoiceId, (string)Request::post('name', true), (int)Request::post('price', true))) {
            Redirect::to('Invoices/Details?id=' . $invoiceId);
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function showInvoicesByYear()
    {
        Redirect::to('Invoices?Year=' . Request::post('year'));
    }

    public function index()
    {
        if (Authorization::hasPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function add()
    {
        if (Authorization::hasPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/Add');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function details()
    {
        if (Authorization::hasPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/Details');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function createPDF()
    {
        if (Authorization::hasPermission('rental-invoices')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Invoices/CreatePDF');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }
}
