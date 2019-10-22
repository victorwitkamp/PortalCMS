<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Modules\Contracts\ContractModel;

/**
 * ContractController
 * Controls everything that is contract-related
 */
class ContractController extends Controller
{
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
}
