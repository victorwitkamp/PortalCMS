<?php

/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;

class EmailController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Route: Batches.
     */
    public function batches()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('mail-scheduler');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Email/Batches');
    }

    /**
     * Route: Messages.
     */
    public function messages()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('mail-scheduler');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Email/Messages');
    }

    /**
     * Route: History.
     */
    public function history()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('mail-scheduler');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Email/History');
    }

    /**
     * Route: Details.
     */
    public function details()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('mail-scheduler');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Email/Details');
    }

    /**
     * Route: ViewTemplates.
     */
    public function viewTemplates()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('mail-templates');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Email/ViewTemplates');
    }

    /**
     * Route: EditTemplates.
     */
    public function editTemplate()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('mail-templates');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Email/EditTemplate');
    }

    /**
     * Route: NewTemplates.
     */
    public function newTemplates()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('mail-templates');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Email/NewTemplate');
    }
}
