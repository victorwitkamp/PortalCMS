<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Members\Member;
use PortalCMS\Modules\Members\MemberAddress;
use PortalCMS\Modules\Members\MemberContactDetails;
use PortalCMS\Modules\Members\MemberModel;
use PortalCMS\Modules\Members\MemberPaymentDetails;
use PortalCMS\Modules\Members\MemberPreferences;

/**
 * Class MembershipController
 * @package PortalCMS\Controllers
 */
class MembershipController extends Controller
{
    private $requests = [
        'saveMember' => 'POST', 'saveNewMember' => 'POST', 'deleteMembersById' => 'POST', 'setPaymentStatusById' => 'POST', 'showMembersByYear' => 'POST', 'copyMembersById' => 'POST'
    ];

    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    public function index() : void
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function new() : void
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/New');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function edit() : void
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/Edit');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function newFromExisting() : void
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/NewFromExisting');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function profile() : void
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/Profile');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public static function saveMember() : void
    {
        if (MemberModel::updateMember(new Member((int)Request::post('id', true), (int)Request::post('jaarlidmaatschap', true), Request::post('voorletters', true), Request::post('voornaam', true), Request::post('achternaam', true), Request::post('geboortedatum', true), new MemberAddress(Request::post('adres', true), Request::post('postcode', true), Request::post('huisnummer', true), Request::post('woonplaats', true)), new MemberContactDetails(Request::post('telefoon_vast', true), Request::post('telefoon_mobiel', true), Request::post('emailadres', true)), Request::post('ingangsdatum', true), Request::post('geslacht', true), new MemberPreferences((int)Request::post('nieuwsbrief', true), (int)Request::post('vrijwilliger', true), (int)Request::post('vrijwilligeroptie1', true), (int)Request::post('vrijwilligeroptie2', true), (int)Request::post('vrijwilligeroptie3', true), (int)Request::post('vrijwilligeroptie4', true), (int)Request::post('vrijwilligeroptie5', true)), new MemberPaymentDetails((string)Request::post('betalingswijze', true), (string)Request::post('iban', true), (string)Request::post('machtigingskenmerk', true), (int)Request::post('status', true))))) {
            Session::add('feedback_positive', 'Lid opgeslagen.');
        } else {
            Session::add('feedback_negative', 'Lid opslaan mislukt.');
        }
        Redirect::to('membership/');
    }

    public static function saveNewMember() : void
    {
        MemberModel::createMember(new Member(null, (int)Request::post('jaarlidmaatschap', true), Request::post('voorletters', true), Request::post('voornaam', true), Request::post('achternaam', true), Request::post('geboortedatum', true), new MemberAddress(Request::post('adres', true), Request::post('postcode', true), Request::post('huisnummer', true), Request::post('woonplaats', true)), new MemberContactDetails(Request::post('telefoon_vast', true), Request::post('telefoon_mobiel', true), Request::post('emailadres', true)), Request::post('ingangsdatum', true), Request::post('geslacht', true), new MemberPreferences((int)Request::post('nieuwsbrief', true), (int)Request::post('vrijwilliger', true), (int)Request::post('vrijwilligeroptie1', true), (int)Request::post('vrijwilligeroptie2', true), (int)Request::post('vrijwilligeroptie3', true), (int)Request::post('vrijwilligeroptie4', true), (int)Request::post('vrijwilligeroptie5', true)), new MemberPaymentDetails((string)Request::post('betalingswijze', true), (string)Request::post('iban', true), (string)Request::post('machtigingskenmerk', true), (int)Request::post('status', true))));
    }

    public static function deleteMembersById() : void
    {
        $ids = (array) Request::post('id');
        foreach ($ids as $id) {
            MemberModel::delete((int) $id);
        }
    }

    public static function setPaymentStatusById() : void
    {
        $status = (int) Request::post('status');
        $ids = (array) Request::post('id');
        foreach ($ids as $id) {
            MemberModel::setStatus((int) $id, $status);
        }
    }

    public static function showMembersByYear() : void
    {
        Redirect::to('Membership?year=' . Request::post('year'));
    }

    public static function copyMembersById() : void
    {
        //            MemberModel::copyMembersById();
        $targetYear = (int) Request::post('targetYear', true);
        $ids = (array) Request::post('id');
        foreach ($ids as $id) {
            MemberModel::copyMember((int) $id, $targetYear);
        }
    }
}
