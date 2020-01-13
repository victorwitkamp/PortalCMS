<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Modules\Members\MemberModel;

class MembershipController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        Authentication::checkAuthentication();

        if (isset($_POST['saveMember'])) {
            MemberModel::saveMember();
        }
        if (isset($_POST['saveNewMember'])) {
            MemberModel::newMember();
        }
        if (isset($_POST['deleteMember'])) {
            MemberModel::delete((int)Request::post('id'));
            Redirect::to('membership/');
        }
        if (isset($_POST['showMembersByYear'])) {
            Redirect::to('membership/?year=' . Request::post('year'));
        }
    }

    public function index()
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function new()
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/New');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function edit()
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/Edit');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function profile()
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/Profile');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }
}
