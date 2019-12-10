<?php

/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;

class EmailController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        Authentication::checkAuthentication();
    }

    /**
     * Route: Batches.
     */
    public function batches()
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/Batches');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: Messages.
     */
    public function messages()
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/Messages');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: History.
     */
    public function history()
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/History');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: Details.
     */
    public function details()
    {
        if (Authorization::hasPermission('mail-scheduler')) {
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/Details');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: ViewTemplates.
     */
    public function viewTemplates()
    {
        if (Authorization::hasPermission('mail-templates')) {
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/ViewTemplates');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: EditTemplates.
     */
    public function editTemplate()
    {
        if (Authorization::hasPermission('mail-templates')) {
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/EditTemplate');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: NewTemplates.
     */
    public function newTemplate()
    {
        if (Authorization::hasPermission('mail-templates')) {
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/NewTemplate');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: NewTemplates.
     */
    public function generate()
    {
        if (Authorization::hasPermission('mail-templates')) {
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/Generate');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: NewTemplates.
     */
    public function generateMember()
    {
        if (Authorization::hasPermission('mail-templates')) {
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Email/GenerateMember');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }
}
