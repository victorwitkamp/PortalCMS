<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
use League\Plates\Engine;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Modules\Members\Member;
use PortalCMS\Modules\Members\MemberAddress;
use PortalCMS\Modules\Members\MemberContactDetails;
use PortalCMS\Modules\Members\MemberModel;
use PortalCMS\Modules\Members\MemberPaymentDetails;
use PortalCMS\Modules\Members\MemberPreferences;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MembershipController
 * @package PortalCMS\Controllers
 */
class MembershipController
{
    protected $templates;

    private $requests = [
        'saveMember'           => 'POST',
        'saveNewMember'        => 'POST',
        'deleteMembersById'    => 'POST',
        'setPaymentStatusById' => 'POST',
        'showMembersByYear'    => 'POST',
        'copyMembersById'      => 'POST'
    ];

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public function index(): ResponseInterface
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/Index');
        } else {
            return new RedirectResponse('/Error/PermissionError');
        }
    }

    public function new(): ResponseInterface
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/New');
        } else {
            return new RedirectResponse('/Error/PermissionError');
        }
    }

    public function edit(): ResponseInterface
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/Edit');
        } else {
            return new RedirectResponse('/Error/PermissionError');
        }
    }

    public function newFromExisting(): ResponseInterface
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/NewFromExisting');
        } else {
            return new RedirectResponse('/Error/PermissionError');
        }
    }

    public function profile(): ResponseInterface
    {
        if (Authorization::hasPermission('membership')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Membership/Profile');
        } else {
            return new RedirectResponse('/Error/PermissionError');
        }
    }

    public static function saveMember(): ResponseInterface
    {
        MemberModel::updateMember(new Member((int) Request::post('id', true), (int) Request::post('jaarlidmaatschap', true), Request::post('voorletters', true), Request::post('voornaam', true), Request::post('achternaam', true), Request::post('geboortedatum', true), new MemberAddress(Request::post('adres', true), Request::post('postcode', true), Request::post('huisnummer', true), Request::post('woonplaats', true)), new MemberContactDetails(Request::post('telefoon_vast', true), Request::post('telefoon_mobiel', true), Request::post('emailadres', true)), Request::post('ingangsdatum', true), Request::post('geslacht', true), new MemberPreferences((int) Request::post('nieuwsbrief', true), (int) Request::post('vrijwilliger', true), (int) Request::post('vrijwilligeroptie1', true), (int) Request::post('vrijwilligeroptie2', true), (int) Request::post('vrijwilligeroptie3', true), (int) Request::post('vrijwilligeroptie4', true), (int) Request::post('vrijwilligeroptie5', true)), new MemberPaymentDetails((string) Request::post('betalingswijze', true), (string) Request::post('iban', true), (string) Request::post('machtigingskenmerk', true), (int) Request::post('status', true))));
        return new RedirectResponse('/Membership');
    }

    public static function saveNewMember(): ResponseInterface
    {
        MemberModel::createMember(new Member(null, (int) Request::post('jaarlidmaatschap', true), Request::post('voorletters', true), Request::post('voornaam', true), Request::post('achternaam', true), Request::post('geboortedatum', true), new MemberAddress(Request::post('adres', true), Request::post('postcode', true), Request::post('huisnummer', true), Request::post('woonplaats', true)), new MemberContactDetails(Request::post('telefoon_vast', true), Request::post('telefoon_mobiel', true), Request::post('emailadres', true)), Request::post('ingangsdatum', true), Request::post('geslacht', true), new MemberPreferences((int) Request::post('nieuwsbrief', true), (int) Request::post('vrijwilliger', true), (int) Request::post('vrijwilligeroptie1', true), (int) Request::post('vrijwilligeroptie2', true), (int) Request::post('vrijwilligeroptie3', true), (int) Request::post('vrijwilligeroptie4', true), (int) Request::post('vrijwilligeroptie5', true)), new MemberPaymentDetails((string) Request::post('betalingswijze', true), (string) Request::post('iban', true), (string) Request::post('machtigingskenmerk', true), (int) Request::post('status', true))));
        return new RedirectResponse('/Membership');
    }

    public static function deleteMembersById(): ResponseInterface
    {
        $ids = (array) Request::post('id');
        foreach ($ids as $id) {
            MemberModel::delete((int) $id);
        }
    }

    public static function setPaymentStatusById(): ResponseInterface
    {
        $status = (int) Request::post('status');
        $ids = (array) Request::post('id');
        foreach ($ids as $id) {
            MemberModel::setStatus((int) $id, $status);
        }
    }

    public static function showMembersByYear(): ResponseInterface
    {
        return new RedirectResponse('/Membership?year=' . Request::post('year'));
    }

    public static function copyMembersById(): ResponseInterface
    {
        $targetYear = (int) Request::post('targetYear', true);
        $ids = (array) Request::post('id');
        foreach ($ids as $id) {
            MemberModel::copyMember((int) $id, $targetYear);
        }
    }
}
