<?php

namespace PortalCMS\Models;

use PortalCMS\Core\Database\DB;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;

class Member
{
    public static function getMembers()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM members ORDER BY id");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function getMembersWithValidEmail()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM members WHERE emailadres IS NOT NULL ORDER BY id");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function doesMemberIdExist($memberId)
    {
        $stmt = DB::conn()->prepare("SELECT id FROM members WHERE id = ? LIMIT 1");
        $stmt->execute([$memberId]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return true;
    }

    public static function doesEmailforYearExist($jaarlidmaatschap, $email)
    {
        $stmt = DB::conn()->prepare("SELECT id FROM members WHERE jaarlidmaatschap = ? AND emailadres = ? LIMIT 1");
        $stmt->execute([$jaarlidmaatschap, $email]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return true;
    }

    public static function getMemberById($id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM members WHERE id=? LIMIT 1");
        $stmt->execute([$id]);
        if (!$stmt->rowCount() == 1) {
            Session::add('feedback_negative', "Lid kan niet worden geladen.");
            return false;
        }
        return $stmt->fetch();
    }

    public static function saveMember()
    {
        $id                     = Request::post('id', true);
        $jaarlidmaatschap       = Request::post('jaarlidmaatschap', true);
        $voorletters            = Request::post('voorletters', true);
        $voornaam               = Request::post('voornaam', true);
        $achternaam             = Request::post('achternaam', true);
        $geboortedatum          = Request::post('geboortedatum', true);
        $adres                  = Request::post('adres', true);
        $postcode               = Request::post('postcode', true);
        $huisnummer             = Request::post('huisnummer', true);
        $woonplaats             = Request::post('woonplaats', true);
        $telefoon_vast          = Request::post('telefoon_vast', true);
        $telefoon_mobiel        = Request::post('telefoon_mobiel', true);
        $emailadres             = Request::post('emailadres', true);
        $ingangsdatum           = Request::post('ingangsdatum', true);
        $geslacht               = Request::post('geslacht', true);
        $nieuwsbrief            = Request::post('nieuwsbrief', true);
        $vrijwilliger           = Request::post('vrijwilliger', true);
        $vrijwilligeroptie1     = Request::post('vrijwilligeroptie1', true);
        $vrijwilligeroptie2     = Request::post('vrijwilligeroptie2', true);
        $vrijwilligeroptie3     = Request::post('vrijwilligeroptie3', true);
        $vrijwilligeroptie4     = Request::post('vrijwilligeroptie4', true);
        $vrijwilligeroptie5     = Request::post('vrijwilligeroptie5', true);
        $betalingswijze         = Request::post('betalingswijze', true);
        $iban                   = Request::post('iban', true);
        $machtigingskenmerk     = Request::post('machtigingskenmerk', true);
        $status = Request::post('status', true);
        // $opmerking              = Request::post('opmerking', true);

        $sql = "UPDATE members
        SET jaarlidmaatschap=?, voorletters=?, voornaam=?, achternaam=?,
        geboortedatum=?, adres=?, postcode=?, huisnummer=?,
        woonplaats=?, telefoon_vast=?, telefoon_mobiel=?,
        emailadres=?, ingangsdatum=?, geslacht=?, nieuwsbrief=?,
        vrijwilliger=?, vrijwilligeroptie1=?, vrijwilligeroptie2=?,
        vrijwilligeroptie3=?, vrijwilligeroptie4=?, vrijwilligeroptie5=?,
        betalingswijze=?, iban=?, machtigingskenmerk=?, status=? WHERE id=?";
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(
            [$jaarlidmaatschap, $voorletters, $voornaam, $achternaam, $geboortedatum,
            $adres, $postcode, $huisnummer, $woonplaats, $telefoon_vast, $telefoon_mobiel,
            $emailadres, $ingangsdatum, $geslacht, $nieuwsbrief, $vrijwilliger, $vrijwilligeroptie1,
            $vrijwilligeroptie2, $vrijwilligeroptie3, $vrijwilligeroptie4, $vrijwilligeroptie5, $betalingswijze, $iban, $machtigingskenmerk,
            $status, $id]
        );
        if ($stmt) {
            Session::add('feedback_positive', "Lid opgeslagen.");
            Redirect::to("membership/");
        }
        Session::add('feedback_negative', "Lid opslaan mislukt.");
        Redirect::to("membership/");
    }

    public static function newMember()
    {
        $jaarlidmaatschap       = Request::post('jaarlidmaatschap', true);
        $voorletters            = Request::post('voorletters', true);
        $voornaam               = Request::post('voornaam', true);
        $achternaam             = Request::post('achternaam', true);
        $geboortedatum          = Request::post('geboortedatum', true);
        $adres                  = Request::post('adres', true);
        $postcode               = Request::post('postcode', true);
        $huisnummer             = Request::post('huisnummer', true);
        $woonplaats             = Request::post('woonplaats', true);
        $telefoon_vast          = Request::post('telefoon_vast', true);
        $telefoon_mobiel        = Request::post('telefoon_mobiel', true);
        $emailadres             = Request::post('emailadres', true);
        $ingangsdatum           = Request::post('ingangsdatum', true);
        $geslacht               = Request::post('geslacht', true);
        $nieuwsbrief            = Request::post('nieuwsbrief', true);
        $vrijwilliger           = Request::post('vrijwilliger', true);
        $vrijwilligeroptie1     = Request::post('vrijwilligeroptie1', true);
        $vrijwilligeroptie2     = Request::post('vrijwilligeroptie2', true);
        $vrijwilligeroptie3     = Request::post('vrijwilligeroptie3', true);
        $vrijwilligeroptie4     = Request::post('vrijwilligeroptie4', true);
        $vrijwilligeroptie5     = Request::post('vrijwilligeroptie5', true);
        $betalingswijze         = Request::post('betalingswijze', true);
        $iban                   = Request::post('iban', true);
        $machtigingskenmerk     = Request::post('machtigingskenmerk', true);
        $status = Request::post('status', true);
        // $opmerking              = Request::post('opmerking', true);

        if (self::doesEmailforYearExist($jaarlidmaatschap, $emailadres)) {
            Session::add('feedback_negative', "Emailadres wordt dit jaar al gebruikt door een ander lid.");
            Redirect::to("membership/");
        } else {
            $sql = "INSERT INTO members
                        (
                            id, jaarlidmaatschap, voorletters, voornaam, achternaam, geboortedatum,
                            adres, postcode, huisnummer, woonplaats, telefoon_vast, telefoon_mobiel,
                            emailadres, ingangsdatum, geslacht, nieuwsbrief, vrijwilliger, vrijwilligeroptie1,
                            vrijwilligeroptie2, vrijwilligeroptie3, vrijwilligeroptie4, vrijwilligeroptie5, betalingswijze, iban, machtigingskenmerk, status
                        )
                        VALUES
                        (
                            NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                        )";
            $stmt = DB::conn()->prepare($sql);
            $stmt->execute(
                [$jaarlidmaatschap, $voorletters, $voornaam, $achternaam, $geboortedatum,
                $adres, $postcode, $huisnummer, $woonplaats, $telefoon_vast, $telefoon_mobiel,
                $emailadres, $ingangsdatum, $geslacht, $nieuwsbrief, $vrijwilliger, $vrijwilligeroptie1,
                $vrijwilligeroptie2, $vrijwilligeroptie3, $vrijwilligeroptie4, $vrijwilligeroptie5, $betalingswijze, $iban, $machtigingskenmerk, $status]
            );
            if ($stmt) {
                Session::add('feedback_positive', "Lid toegevoegd.");
                Redirect::to("membership/");
            }
            Session::add('feedback_negative', "Lid toevoegen mislukt.");
            Redirect::to("membership/");
        }
    }
}
