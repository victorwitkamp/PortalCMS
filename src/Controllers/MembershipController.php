<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Modules\Members\MemberModel;

class MembershipController extends Controller
{
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
    }
}
