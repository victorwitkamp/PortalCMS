<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

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

        if (isset($_POST['saveMember'])) {
            MemberModel::saveMember();
        }
        if (isset($_POST['saveNewMember'])) {
            MemberModel::newMember();
        }
        if (isset($_POST['deleteMember'])) {
            MemberModel::delete((int) Request::post('id'));
            Redirect::to('membership/');
        }
        if (isset($_POST['showMembersByYear'])) {
            Redirect::to('membership/?year=' . Request::post('year'));
        }
    }

    public function index()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('membership');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Membership/Index');
    }

    public function new()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('membership');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Membership/New');
    }

    public function edit()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('membership');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Membership/Edit');
    }
}
