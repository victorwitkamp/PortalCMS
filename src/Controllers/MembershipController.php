<?php

namespace PortalCMS\Controllers;

use PortalCMS\Models\MemberModel;
use PortalCMS\Core\Controllers\Controller;

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
    }
}
