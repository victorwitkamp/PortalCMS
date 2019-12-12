<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Modules\Contracts\ContractModel;

/**
 * ContractsController
 */
class ContractsController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        Authentication::checkAuthentication();

        if (isset($_POST['updateContract'])) {
            ContractModel::update();
        }
        if (isset($_POST['newContract'])) {
            ContractModel::new();
        }
        if (isset($_POST['deleteContract'])) {
            ContractModel::delete();
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

    public function view()
    {
        if (Authorization::hasPermission('rental-contracts')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Contracts/View');
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
