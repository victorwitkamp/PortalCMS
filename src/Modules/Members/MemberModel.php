<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Members;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;

class MemberModel
{
    public static function copyMember(int $id = null, int $targetYear = null) : bool
    {
        $member = self::getMember($id);
        if ($member->jaarlidmaatschap === $targetYear) {
            return false;
        }
        $member->id = null;
        $member->jaarlidmaatschap = $targetYear;
        $member->paymentDetails->status = 0;
        $member->creationDate = null;
        $member->modificationDate = null;

        if (MemberMapper::new($member)) {
            return true;
        }
        return false;
    }

    public static function copyMembersById()
    {
        $targetYear = (int) Request::post('targetYear', true);
        $ids = (array) Request::post('id');
        foreach ($ids as $id) {
            self::copyMember($id, $targetYear);
        }
    }

    public static function getMember(int $id): Member
    {
        $membermap = MemberMapper::getMemberById($id);

        return new Member(
            $id,
            $membermap->jaarlidmaatschap,
            $membermap->voorletters,
            $membermap->voornaam,
            $membermap->achternaam,
            $membermap->geboortedatum,
            new MemberAddress(
                $membermap->adres,
                $membermap->postcode,
                $membermap->huisnummer,
                $membermap->woonplaats
            ),
            new MemberContactDetails(
                $membermap->telefoon_vast,
                $membermap->telefoon_mobiel,
                $membermap->emailadres
            ),
            $membermap->ingangsdatum,
            $membermap->geslacht,
            new MemberPreferences(
                $membermap->nieuwsbrief,
                $membermap->vrijwilliger,
                $membermap->vrijwilligeroptie1,
                $membermap->vrijwilligeroptie2,
                $membermap->vrijwilligeroptie3,
                $membermap->vrijwilligeroptie4,
                $membermap->vrijwilligeroptie5
            ),
            new MemberPaymentDetails(
                $membermap->betalingswijze,
                $membermap->iban,
                $membermap->machtigingskenmerk,
                $membermap->status
            )
        );
    }
    public static function delete(int $id): bool
    {
        if (MemberMapper::doesMemberIdExist($id)) {
            if (MemberMapper::delete($id)) {
                Session::add('feedback_positive', 'Lid verwijderd.');
                return true;
            }
            Session::add('feedback_negative', 'Verwijderen van lid mislukt.');
        } else {
            Session::add('feedback_negative', 'Verwijderen van lid. Evenement bestaat niet.');
        }
        return false;
    }

    public static function saveMember(Member $member = null)
    {
        if (MemberMapper::updateMember($member)) {
            Session::add('feedback_positive', 'Lid opgeslagen.');
            Redirect::to('membership/');
        }
        Session::add('feedback_negative', 'Lid opslaan mislukt.');
        Redirect::to('membership/');
    }

    public static function newMember(Member $member)
    {
        if (MemberMapper::doesEmailforYearExist($member->jaarlidmaatschap, $member->contactDetails->emailadres)) {
            Session::add('feedback_negative', 'Emailadres wordt dit jaar al gebruikt door een ander lid.');
            Redirect::to('membership/');
        } else {
            if (MemberMapper::new($member)) {
                Session::add('feedback_positive', 'Lid toegevoegd.');
                Redirect::to('membership/');
            }
            Session::add('feedback_negative', 'Lid toevoegen mislukt.');
            Redirect::to('membership/');
        }
    }
}
