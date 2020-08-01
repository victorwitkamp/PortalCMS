<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Members;

use PortalCMS\Core\Database\DB;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;

class MemberModel
{
    public static function copyMembersById()
    {
        $targetYear = (int) Request::post('targetYear', true);
        $ids = (array) Request::post('id');
        foreach ($ids as $id) {
            $member = self::getMember((int) $id);
            var_dump($member);
            if ($member->jaarlidmaatschap === $targetYear) {
                echo 'error. target year is the same as current year.';
            } else {
                $member->id = null;
                $member->jaarlidmaatschap = $targetYear;
                $member->paymentDetails->status = 0;
//                $member->CreationDate = null;
//                $member->ModificationDate = null;
                var_dump($member);
            }
            if (MemberMapper::new($member)) {
                echo 'saved';
            } else {
                echo 'could not be saved';
            }
            echo '<hr>';
        }
        die;
    }

    public static function getMember(int $id)
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
        if (MemberMapper::doesEmailforYearExist($member->jaarlidmaatschap, $member->memberContactDetails->emailadres)) {
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
