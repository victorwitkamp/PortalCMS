<?php

namespace PortalCMS\Controllers;

use PortalCMS\Models\Member;
use PortalCMS\Core\Controllers\Controller;

class MembershipController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['saveMember'])) {
            Member::saveMember();
        }
        if (isset($_POST['saveNewMember'])) {
            Member::newMember();
        }
    }
}
