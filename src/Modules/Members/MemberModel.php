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

        if (MemberMapper::doesEmailforYearExist($jaarlidmaatschap, $emailadres)) {
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
