<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\HTTP\Redirect;
use App\Core\Security\Authentication\Authentication;
use App\Core\Security\Authorization\Authorization;
use App\Modules\Members\Member;
use App\Modules\Members\MemberAddress;
use App\Modules\Members\MemberContactDetails;
use App\Modules\Members\MemberModel;
use App\Modules\Members\MemberPaymentDetails;
use App\Modules\Members\MemberPreferences;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Membership", name="membership")
 */
class MembershipController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();

          if (isset($_POST['deleteMembersById'])) {
            $ids = (array)$this->request->get('id');
            foreach ($ids as $id) {
                MemberModel::delete((int)$id);
            }
            //            Redirect::to('Membership/');
        }

        if (isset($_POST['setPaymentStatusById'])) {
            $status = (int) $this->request->get('status');
            $ids = (array)$this->request->get('id');
            foreach ($ids as $id) {
                MemberModel::setStatus((int)$id, $status);
            }
        }

        if (isset($_POST['showMembersByYear'])) {
            Redirect::to('Membership?year=' . $this->request->get('year'));
        }

        if (isset($_POST['copyMembersById'])) {
//            MemberModel::copyMembersById();
            $targetYear = (int)$this->request->get('targetYear');
            $ids = (array) $this->request->get('id');
            foreach ($ids as $id) {
                MemberModel::copyMember((int) $id, $targetYear);
            }
        }
    }

    /**
     * @Route("/", name="")
     */
    public function index() : Response
    {
        if (Authorization::hasPermission('membership')) {

            return $this->render('Pages/Membership/Index');
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }

    /**
     * @Route("/New", name="new")
     */
    public function new(Request $request) : Response
    {
        if (Authorization::hasPermission('membership')) {
            if ($this->request->isMethod('POST')) {
                MemberModel::createMember(new Member(null, (int)
                $this->request->get('jaarlidmaatschap'),
                    $this->request->get('voorletters'), $this->request->get('voornaam'), $this->request->get('achternaam'), $this->request->get('geboortedatum'), new MemberAddress($this->request->get('adres'), $this->request->get('postcode'), $this->request->get('huisnummer'), $this->request->get('woonplaats')), new MemberContactDetails($this->request->get('telefoon_vast'), $this->request->get('telefoon_mobiel'), $this->request->get('emailadres')), $this->request->get('ingangsdatum'), $this->request->get('geslacht'), new MemberPreferences((int)$this->request->get('nieuwsbrief'), (int)$this->request->get('vrijwilliger'), (int)$this->request->get('vrijwilligeroptie1'), (int)$this->request->get('vrijwilligeroptie2'), (int)$this->request->get('vrijwilligeroptie3'), (int)$this->request->get('vrijwilligeroptie4'), (int)$this->request->get('vrijwilligeroptie5')), new MemberPaymentDetails((string)$this->request->get('betalingswijze'), (string)$this->request->get('iban'), (string)$this->request->get('machtigingskenmerk'), (int)$this->request->get('status'))));
            }
            return $this->render('Pages/Membership/New');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Edit", name="edit")
     */
    public function edit(Request $request) : Response
    {
        if (Authorization::hasPermission('membership')) {
            if ($this->request->isMethod('POST')) {
                $data = $this->request->toArray();

                if (MemberModel::updateMember(
                    new Member(
                        (int)$this->request->get('id'), (int)$this->request->get('jaarlidmaatschap'), $this->request->get('voorletters'), $this->request->get('voornaam'), $this->request->get('achternaam'), $this->request->get('geboortedatum'), new MemberAddress($this->request->get('adres'), $this->request->get('postcode'), $this->request->get('huisnummer'), $this->request->get('woonplaats')), new MemberContactDetails($this->request->get('telefoon_vast'), $this->request->get('telefoon_mobiel'), $this->request->get('emailadres')), $this->request->get('ingangsdatum'), $this->request->get('geslacht'), new MemberPreferences((int)$this->request->get('nieuwsbrief'), (int)$this->request->get('vrijwilliger'), (int)$this->request->get('vrijwilligeroptie1'), (int)$this->request->get('vrijwilligeroptie2'), (int)$this->request->get('vrijwilligeroptie3'), (int)$this->request->get('vrijwilligeroptie4'), (int)$this->request->get('vrijwilligeroptie5')), new MemberPaymentDetails((string)$this->request->get('betalingswijze'), (string)$this->request->get('iban'), (string)$this->request->get('machtigingskenmerk'), (int)$this->request->get('status'))))) {
                    $this->addFlash('success','Lid opgeslagen.');
                } else {
                    $this->addFlash('danger','Lid opslaan mislukt.');
                }
                //todo return $this->redirectToRoute('membership);
            }
            return $this->render('Pages/Membership/Edit');
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }

    /**
     * @Route("/NewFromExisting", name="newfromexisting")
     */
    public function newFromExisting() : Response
    {
        if (Authorization::hasPermission('membership')) {

            return $this->render('Pages/Membership/NewFromExisting');
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }

    /**
     * @Route("/Profile", name="profile")
     */
    public function profile() : Response
    {
        if (Authorization::hasPermission('membership')) {
            return $this->render('Profile', []);
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }
}
