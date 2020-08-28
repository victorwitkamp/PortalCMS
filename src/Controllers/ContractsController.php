<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Modules\Contracts\ContractFactory;

/**
 * Class ContractsController
 * @package PortalCMS\Controllers
 */
class ContractsController
{
    protected $templates;

    private $requests = [
        'newContract' => 'POST', 'updateContract' => 'POST', 'deleteContract' => 'POST'
    ];

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public static function newContract() : void
    {
        ContractFactory::new();
        Redirect::to('Contracts/');
    }

    public static function updateContract() : void
    {
        ContractFactory::update();
        Redirect::to('Contracts/');
    }

    public static function deleteContract() : void
    {
        if (ContractFactory::delete((int) Request::post('id'))) {
            Redirect::to('Contracts/');
        }
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
}
