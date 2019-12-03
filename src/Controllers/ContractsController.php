<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
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

    public function index()
    {
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Contracts/index');
    }
}
