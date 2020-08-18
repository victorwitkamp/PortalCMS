<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Members;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;

/**
 * Class MemberModel
 * @package PortalCMS\Modules\Members
 */
class MemberModel
{
    /**
     * @param int|null $id
     * @param int|null $status
     */
    public static function setStatus(int $id = null, int $status = null) : bool
    {
        if (($id !== null) && $status !== null) {
            if (MemberMapper::doesMemberIdExist($id)) {
                if (MemberMapper::setStatus($id, $status)) {
                    Session::add('feedback_positive', 'Status gewijzigd naar "' . $status . '" voor lid met ID: ' . $id);
                    return true;
                }
                Session::add('feedback_negative', 'Status wijzigen mislukt.');
            } else {
                Session::add('feedback_negative', 'Kan status niet wijzigen. Lid bestaat niet.');
            }
        }
        return false;
    }

    /**
     * @param int|null $id
     * @param int|null $targetYear
     */
    public static function copyMember(int $id = null, int $targetYear = null): bool
    {
        if ($id === null || $targetYear === null) {
            return false;
        }
        $member = self::getMember($id);
        if ($member->jaarlidmaatschap === $targetYear) {
            return false;
        }
        $member->id = null;
        $member->jaarlidmaatschap = $targetYear;
        $member->paymentDetails->status = 0;
        $member->creationDate = null;
        $member->modificationDate = null;

        return (MemberMapper::new($member));
    }

    /**
     * @param int|null $id
     */
    public static function getMember(int $id = null): ?Member
    {
        if ($id === null) {
            return null;
        }
        $membermap = MemberMapper::getMemberById($id);
        return new Member($id, $membermap->jaarlidmaatschap, $membermap->voorletters, $membermap->voornaam, $membermap->achternaam, $membermap->geboortedatum, new MemberAddress($membermap->adres, $membermap->postcode, $membermap->huisnummer, $membermap->woonplaats), new MemberContactDetails($membermap->telefoon_vast, $membermap->telefoon_mobiel, $membermap->emailadres), $membermap->ingangsdatum, $membermap->geslacht, new MemberPreferences($membermap->nieuwsbrief, $membermap->vrijwilliger, $membermap->vrijwilligeroptie1, $membermap->vrijwilligeroptie2, $membermap->vrijwilligeroptie3, $membermap->vrijwilligeroptie4, $membermap->vrijwilligeroptie5), new MemberPaymentDetails($membermap->betalingswijze, $membermap->iban, $membermap->machtigingskenmerk, $membermap->status));
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function delete(int $id = null): bool
    {
        if (MemberMapper::doesMemberIdExist($id)) {
            if (MemberMapper::delete($id)) {
                Session::add('feedback_positive', 'Lid verwijderd (ID: '.$id.').');
                return true;
            }
            Session::add('feedback_negative', 'Verwijderen van lid mislukt.');
        } else {
            Session::add('feedback_negative', 'Verwijderen van lid mislukt. Lid bestaat niet.');
        }
        return false;
    }

    public static function updateMember(Member $member = null): bool
    {
        if ($member === null) {
            return false;
        }
        return (MemberMapper::updateMember($member));
    }

    /**
     * @param Member|null $member
     */
    public static function createMember(Member $member = null)
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
