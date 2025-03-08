<?php


declare(strict_types=1);

namespace App\Modules\Members;

use App\Core\HTTP\Redirect;

class MemberModel
{
    public function setStatus(int $id = null, int $status = null) : bool
    {
        if (MemberMapper::doesMemberIdExist($id)) {
            if (MemberMapper::setStatus($id, $status)) {
                $this->addFlash('success','Status gewijzigd naar "' . $status . '" voor lid met ID: ' . $id);
                return true;
            }
            $this->addFlash('danger','Verwijderen van lid mislukt.');
        } else {
            $this->addFlash('danger','Verwijderen van lid mislukt. Lid bestaat niet.');
        }
        return false;
    }

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

        if (MemberMapper::new($member)) {
            return true;
        }
        return false;
    }

    public static function getMember(int $id): Member
    {
        $membermap = MemberMapper::getMemberById($id);

        return new Member($id, $membermap->jaarlidmaatschap, $membermap->voorletters, $membermap->voornaam, $membermap->achternaam, $membermap->geboortedatum, new MemberAddress($membermap->adres, $membermap->postcode, $membermap->huisnummer, $membermap->woonplaats), new MemberContactDetails($membermap->telefoon_vast, $membermap->telefoon_mobiel, $membermap->emailadres), $membermap->ingangsdatum, $membermap->geslacht, new MemberPreferences($membermap->nieuwsbrief, $membermap->vrijwilliger, $membermap->vrijwilligeroptie1, $membermap->vrijwilligeroptie2, $membermap->vrijwilligeroptie3, $membermap->vrijwilligeroptie4, $membermap->vrijwilligeroptie5), new MemberPaymentDetails($membermap->betalingswijze, $membermap->iban, $membermap->machtigingskenmerk, $membermap->status));
    }

    public function delete(int $id): bool
    {
        if (MemberMapper::doesMemberIdExist($id)) {
            if (MemberMapper::delete($id)) {
                $this->addFlash('success','Lid verwijderd (ID: '.$id.').');
                return true;
            }
            $this->addFlash('danger','Verwijderen van lid mislukt.');
        } else {
            $this->addFlash('danger','Verwijderen van lid mislukt. Lid bestaat niet.');
        }
        return false;
    }

    public function updateMember(Member $member = null): bool
    {
        return (MemberMapper::updateMember($member));
    }

    public function createMember(Member $member = null)
    {
        if (MemberMapper::doesEmailforYearExist($member->jaarlidmaatschap, $member->contactDetails->emailadres)) {
            $this->addFlash('danger','Emailadres wordt dit jaar al gebruikt door een ander lid.');
            Redirect::to('membership/');
        } else {
            if (MemberMapper::new($member)) {
                $this->addFlash('success','Lid toegevoegd.');
                Redirect::to('membership/');
            }
            $this->addFlash('danger','Lid toevoegen mislukt.');
            Redirect::to('membership/');
        }
    }
}
