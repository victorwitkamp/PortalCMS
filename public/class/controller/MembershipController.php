<?php

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
