<?php

class Member
{
    public static function getMembers()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM members ORDER BY id");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }
    public static function doesMemberIdExist($memberId)
    {
        $stmt = DB::conn()->prepare("SELECT id FROM members WHERE id = ? LIMIT 1");
        $stmt->execute([$memberId]);
        if ($stmt->rowCount() == 0) {
            return FALSE;
        }
        return TRUE;
    }

    public static function doesEmailforYearExist($jaarlidmaatschap, $email)
    {
        $stmt = DB::conn()->prepare("SELECT id FROM members WHERE jaarlidmaatschap = ? AND emailadres = ? LIMIT 1");
        $stmt->execute([$jaarlidmaatschap, $email]);
        if ($stmt->rowCount() == 0) {
            return FALSE;
        }
        return TRUE;
    }

    public static function getMemberById($id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM members WHERE id=? LIMIT 1");
        $stmt->execute([$id]);
        if (!$stmt->rowCount() == 1) {
            Session::add('feedback_negative', "Lid kan niet worden geladen.");
            return FALSE;
        }
        return $stmt->fetch();
    }

    static function saveMember()
    {
        $id                     = Request::post('id', TRUE);
        $jaarlidmaatschap       = Request::post('jaarlidmaatschap', TRUE);
        $voorletters            = Request::post('voorletters', TRUE);
        $voornaam               = Request::post('voornaam', TRUE);
        $achternaam             = Request::post('achternaam', TRUE);
        $geboortedatum          = Request::post('geboortedatum', TRUE);
        $adres                  = Request::post('adres', TRUE);
        $postcode               = Request::post('postcode', TRUE);
        $huisnummer             = Request::post('huisnummer', TRUE);
        $woonplaats             = Request::post('woonplaats', TRUE);
        $telefoon_vast          = Request::post('telefoon_vast', TRUE);
        $telefoon_mobiel        = Request::post('telefoon_mobiel', TRUE);
        $emailadres             = Request::post('emailadres', TRUE);
        $ingangsdatum           = Request::post('ingangsdatum', TRUE);
        $geslacht               = Request::post('geslacht', TRUE);
        $nieuwsbrief            = Request::post('nieuwsbrief', TRUE);
        $vrijwilliger           = Request::post('vrijwilliger', TRUE);
        $vrijwilligeroptie1     = Request::post('vrijwilligeroptie1', TRUE);
        $vrijwilligeroptie2     = Request::post('vrijwilligeroptie2', TRUE);
        $vrijwilligeroptie3     = Request::post('vrijwilligeroptie3', TRUE);
        $vrijwilligeroptie4     = Request::post('vrijwilligeroptie4', TRUE);
        $vrijwilligeroptie5     = Request::post('vrijwilligeroptie5', TRUE);
        $betalingswijze         = Request::post('betalingswijze', TRUE);
        $iban                   = Request::post('iban', TRUE);
        $machtigingskenmerk     = Request::post('machtigingskenmerk', TRUE);
        $incasso_gelukt         = Request::post('incasso_gelukt', TRUE);
        $opmerking              = Request::post('opmerking', TRUE);

        $sql = "UPDATE members
        SET jaarlidmaatschap=?, voorletters=?, voornaam=?, achternaam=?,
        geboortedatum=?, adres=?, postcode=?, huisnummer=?,
        woonplaats=?, telefoon_vast=?, telefoon_mobiel=?,
        emailadres=?, ingangsdatum=?, geslacht=?, nieuwsbrief=?,
        vrijwilliger=?, vrijwilligeroptie1=?, vrijwilligeroptie2=?,
        vrijwilligeroptie3=?, vrijwilligeroptie4=?, vrijwilligeroptie5=?,
        betalingswijze=?, iban=?, machtigingskenmerk=?, incasso_gelukt=?,
        opmerking=? WHERE id=?";
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(
            [$jaarlidmaatschap, $voorletters, $voornaam, $achternaam, $geboortedatum,
            $adres, $postcode, $huisnummer, $woonplaats, $telefoon_vast, $telefoon_mobiel,
            $emailadres, $ingangsdatum, $geslacht, $nieuwsbrief, $vrijwilliger, $vrijwilligeroptie1,
            $vrijwilligeroptie2, $vrijwilligeroptie3, $vrijwilligeroptie4, $vrijwilligeroptie5, $betalingswijze, $iban, $machtigingskenmerk,
            $incasso_gelukt, $opmerking, $id]
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
        $jaarlidmaatschap       = Request::post('jaarlidmaatschap', TRUE);
        $voorletters            = Request::post('voorletters', TRUE);
        $voornaam               = Request::post('voornaam', TRUE);
        $achternaam             = Request::post('achternaam', TRUE);
        $geboortedatum          = Request::post('geboortedatum', TRUE);
        $adres                  = Request::post('adres', TRUE);
        $postcode               = Request::post('postcode', TRUE);
        $huisnummer             = Request::post('huisnummer', TRUE);
        $woonplaats             = Request::post('woonplaats', TRUE);
        $telefoon_vast          = Request::post('telefoon_vast', TRUE);
        $telefoon_mobiel        = Request::post('telefoon_mobiel', TRUE);
        $emailadres             = Request::post('emailadres', TRUE);
        $ingangsdatum           = Request::post('ingangsdatum', TRUE);
        $geslacht               = Request::post('geslacht', TRUE);
        $nieuwsbrief            = Request::post('nieuwsbrief', TRUE);
        $vrijwilliger           = Request::post('vrijwilliger', TRUE);
        $vrijwilligeroptie1     = Request::post('vrijwilligeroptie1', TRUE);
        $vrijwilligeroptie2     = Request::post('vrijwilligeroptie2', TRUE);
        $vrijwilligeroptie3     = Request::post('vrijwilligeroptie3', TRUE);
        $vrijwilligeroptie4     = Request::post('vrijwilligeroptie4', TRUE);
        $vrijwilligeroptie5     = Request::post('vrijwilligeroptie5', TRUE);
        $betalingswijze         = Request::post('betalingswijze', TRUE);
        $iban                   = Request::post('iban', TRUE);
        // $machtigingskenmerk     = Request::post('machtigingskenmerk', TRUE);
        // $incasso_gelukt         = Request::post('incasso_gelukt', TRUE);
        // $opmerking              = Request::post('opmerking', TRUE);

        if (self::doesEmailforYearExist($jaarlidmaatschap, $emailadres)) {
            Session::add('feedback_negative', "Emailadres wordt dit jaar al gebruikt door een ander lid.");
            Redirect::to("membership/");
        } else {
            $sql = "INSERT INTO members
                        (
                            id, jaarlidmaatschap, voorletters, voornaam, achternaam, geboortedatum,
                            adres, postcode, huisnummer, woonplaats, telefoon_vast, telefoon_mobiel,
                            emailadres, ingangsdatum, geslacht, nieuwsbrief, vrijwilliger, vrijwilligeroptie1,
                            vrijwilligeroptie2, vrijwilligeroptie3, vrijwilligeroptie4, vrijwilligeroptie5, betalingswijze, iban
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
                $vrijwilligeroptie2, $vrijwilligeroptie3, $vrijwilligeroptie4, $vrijwilligeroptie5, $betalingswijze, $iban]
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