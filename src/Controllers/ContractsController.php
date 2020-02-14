<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Modules\Contracts\ContractModel;

class ContractsController extends Controller
{
    private $requests = [
        'newContract' => 'POST',
        'updateContract' => 'POST',
        'deleteContract' => 'POST'
    ];

    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    public function index()
    {
        if (Authorization::hasPermission('rental-contracts')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Contracts/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function new()
    {
        if (Authorization::hasPermission('rental-contracts')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Contracts/New');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function edit()
    {
        if (Authorization::hasPermission('rental-contracts')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Contracts/Edit');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function details()
    {
        if (Authorization::hasPermission('rental-contracts')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Contracts/Details');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function invoices()
    {
        if (Authorization::hasPermission('rental-contracts')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Contracts/Invoices');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public static function newContract()
    {
        ContractModel::new();
        Redirect::to('Contracts/');
    }

    public static function updateContract()
    {
        ContractModel::update();
        Redirect::to('Contracts/');
    }

    public static function deleteContract()
    {
        if (ContractModel::delete((int) Request::post('id'))) {
            Redirect::to('Contracts/');
        }
    }
}
