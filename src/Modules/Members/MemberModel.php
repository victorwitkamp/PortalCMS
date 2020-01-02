<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Members;

use PDO;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;

class MemberModel
{
    /**
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        if (self::doesMemberIdExist($id)) {
            if (self::deleteAction($id)) {
                Session::add('feedback_positive', 'Lid verwijderd.');
                return true;
            }
            Session::add('feedback_negative', 'Verwijderen van lid mislukt.');
        } else {
            Session::add('feedback_negative', 'Verwijderen van lid. Evenement bestaat niet.');
        }
        return false;
    }

    public static function deleteAction(int $id): bool
    {
        $stmt = DB::conn()->prepare('DELETE FROM members WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->rowCount() === 1;
    }

    public static function getMembers() : ?array
    {
        $stmt = DB::conn()->query('SELECT * FROM members ORDER BY id');
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getMembersByYear(int $year) : ?array
    {
        $stmt = DB::conn()->prepare('SELECT * FROM members where jaarlidmaatschap = ? ORDER BY id');
        $stmt->execute([$year]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getMembersWithValidEmail() : ?array
    {
        $stmt = DB::conn()->query('SELECT * FROM members WHERE emailadres IS NOT NULL ORDER BY id');
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function doesMemberIdExist(int $memberId) : bool
    {
        $stmt = DB::conn()->prepare('SELECT id FROM members WHERE id = ? LIMIT 1');
        $stmt->execute([$memberId]);
        return $stmt->rowCount() === 1;
    }

    public static function doesEmailforYearExist(int $jaarlidmaatschap, string $email) : bool
    {
        $stmt = DB::conn()->prepare('SELECT id FROM members WHERE jaarlidmaatschap = ? AND emailadres = ? LIMIT 1');
        $stmt->execute([$jaarlidmaatschap, $email]);
        return $stmt->rowCount() === 1;
    }

    public static function getMemberById(int $id) : ?object
    {
        $stmt = DB::conn()->prepare('SELECT * FROM members WHERE id=? LIMIT 1');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        Session::add('feedback_negative', 'Lid kan niet worden geladen.');
        return null;
    }

    public static function saveMember()
    {
        $id                     = (int) Request::post('id', true);
        $jaarlidmaatschap       = (int) Request::post('jaarlidmaatschap', true);
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
        $nieuwsbrief            = (int) Request::post('nieuwsbrief', true);
        $vrijwilliger           = (int) Request::post('vrijwilliger', true);
        $vrijwilligeroptie1     = (int) Request::post('vrijwilligeroptie1', true);
        $vrijwilligeroptie2     = (int) Request::post('vrijwilligeroptie2', true);
        $vrijwilligeroptie3     = (int) Request::post('vrijwilligeroptie3', true);
        $vrijwilligeroptie4     = (int) Request::post('vrijwilligeroptie4', true);
        $vrijwilligeroptie5     = (int) Request::post('vrijwilligeroptie5', true);
        $betalingswijze         = Request::post('betalingswijze', true);
        $iban                   = Request::post('iban', true);
        $machtigingskenmerk     = Request::post('machtigingskenmerk', true);
        $status                 = (int) Request::post('status', true);
        // $opmerking              = Request::post('opmerking', true);

        $stmt = DB::conn()->prepare(
            'UPDATE members
                SET jaarlidmaatschap=?, voorletters=?, voornaam=?, achternaam=?,
                geboortedatum=?, adres=?, postcode=?, huisnummer=?,
                woonplaats=?, telefoon_vast=?, telefoon_mobiel=?,
                emailadres=?, ingangsdatum=?, geslacht=?, nieuwsbrief=?,
                vrijwilliger=?, vrijwilligeroptie1=?, vrijwilligeroptie2=?,
                vrijwilligeroptie3=?, vrijwilligeroptie4=?, vrijwilligeroptie5=?,
                betalingswijze=?, iban=?, machtigingskenmerk=?, status=? WHERE id=?'
        );
        $stmt->execute(
            [$jaarlidmaatschap, $voorletters, $voornaam, $achternaam,
            $geboortedatum, $adres, $postcode, $huisnummer,
            $woonplaats, $telefoon_vast, $telefoon_mobiel,
            $emailadres, $ingangsdatum, $geslacht, $nieuwsbrief,
            $vrijwilliger, $vrijwilligeroptie1, $vrijwilligeroptie2,
            $vrijwilligeroptie3, $vrijwilligeroptie4, $vrijwilligeroptie5,
            $betalingswijze, $iban, $machtigingskenmerk, $status, $id]
        );
        if ($stmt) {
            Session::add('feedback_positive', 'Lid opgeslagen.');
            Redirect::to('membership/');
        }
        Session::add('feedback_negative', 'Lid opslaan mislukt.');
        Redirect::to('membership/');
    }

    public static function newMember()
    {
        $jaarlidmaatschap       = (int) Request::post('jaarlidmaatschap', true);
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
        $emailadres             = (string) Request::post('emailadres', true);
        $ingangsdatum           = Request::post('ingangsdatum', true);
        $geslacht               = Request::post('geslacht', true);
        $nieuwsbrief            = (int) Request::post('nieuwsbrief', true);
        $vrijwilliger           = (int) Request::post('vrijwilliger', true);
        $vrijwilligeroptie1     = (int) Request::post('vrijwilligeroptie1', true);
        $vrijwilligeroptie2     = (int) Request::post('vrijwilligeroptie2', true);
        $vrijwilligeroptie3     = (int) Request::post('vrijwilligeroptie3', true);
        $vrijwilligeroptie4     = (int) Request::post('vrijwilligeroptie4', true);
        $vrijwilligeroptie5     = (int) Request::post('vrijwilligeroptie5', true);
        $betalingswijze         = Request::post('betalingswijze', true);
        $iban                   = Request::post('iban', true);
        $machtigingskenmerk     = Request::post('machtigingskenmerk', true);
        $status                 = (int) Request::post('status', true);
        // $opmerking              = Request::post('opmerking', true);

        if (self::doesEmailforYearExist($jaarlidmaatschap, $emailadres)) {
            Session::add('feedback_negative', 'Emailadres wordt dit jaar al gebruikt door een ander lid.');
            Redirect::to('membership/');
        } else {
            $stmt = DB::conn()->prepare(
                'INSERT INTO members
                        (
                            id, jaarlidmaatschap, voorletters, voornaam, achternaam, geboortedatum,
                            adres, postcode, huisnummer, woonplaats, telefoon_vast, telefoon_mobiel,
                            emailadres, ingangsdatum, geslacht, nieuwsbrief, vrijwilliger, vrijwilligeroptie1,
                            vrijwilligeroptie2, vrijwilligeroptie3, vrijwilligeroptie4, vrijwilligeroptie5, betalingswijze, iban, machtigingskenmerk, status
                        ) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute(
                [
                    $jaarlidmaatschap, $voorletters, $voornaam, $achternaam, $geboortedatum,
                    $adres, $postcode, $huisnummer, $woonplaats, $telefoon_vast, $telefoon_mobiel,
                    $emailadres, $ingangsdatum, $geslacht, $nieuwsbrief, $vrijwilliger, $vrijwilligeroptie1,
                    $vrijwilligeroptie2, $vrijwilligeroptie3, $vrijwilligeroptie4, $vrijwilligeroptie5, $betalingswijze, $iban, $machtigingskenmerk, $status
                ]
            );
            if ($stmt) {
                Session::add('feedback_positive', 'Lid toegevoegd.');
                Redirect::to('membership/');
            }
            Session::add('feedback_negative', 'Lid toevoegen mislukt.');
            Redirect::to('membership/');
        }
    }
}
