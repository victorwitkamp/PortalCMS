<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
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

    /**
     * Route: Index.
     */
    public function index()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('rental-contracts');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Contracts/Index');
    }

    /**
     * Route: New.
     */
    public function new()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('rental-contracts');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Contracts/New');
    }

    /**
     * Route: Edit.
     */
    public function edit()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('rental-contracts');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Contracts/Edit');
    }

    /**
     * Route: View.
     */
    public function view()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('rental-contracts');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Contracts/View');
    }

    /**
     * Route: Invoices.
     */
    public function invoices()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('rental-contracts');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Contracts/Invoices');
    }
}
