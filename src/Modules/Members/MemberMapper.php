<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Members;

use PDO;
use PortalCMS\Core\Database\DB;

class MemberMapper
{
    public static function getMembers(int $year = null, string $paymentType = null) : ?array
    {
        if (!empty($year) && !empty($paymentType)) {
            $stmt = DB::conn()->prepare('SELECT * FROM members WHERE jaarlidmaatschap = ? AND betalingswijze = ? ORDER BY id');
            $stmt->execute([
                $year,
                $paymentType
            ]);
            return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
        } elseif (!empty($year) && empty($paymentType)) {
            $stmt = DB::conn()->prepare('SELECT * FROM members WHERE jaarlidmaatschap = ? ORDER BY id');
            $stmt->execute([
                $year
            ]);
            return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
        } elseif (empty($year) && !empty($paymentType)) {
            $stmt = DB::conn()->prepare('SELECT * FROM members WHERE betalingswijze = ? ORDER BY id');
            $stmt->execute([
                $paymentType
            ]);
            return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
        } else {
            $stmt = DB::conn()->query('SELECT * FROM members ORDER BY id');
            return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    }

    public static function getMembersByPaymentType(string $paymentType) : ?array
    {
        $stmt = DB::conn()->prepare('SELECT * FROM members where betalingswijze = ? ORDER BY id');
        $stmt->execute([$paymentType]);
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getMembersByYearAndPaymentType(int $year, string $paymentType) : ?array
    {
        $stmt = DB::conn()->prepare('SELECT * FROM members where jaarlidmaatschap = ? and betalingswijze = ? ORDER BY id');
        $stmt->execute([$year, $paymentType]);
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getPaymentTypes() : ?array
    {
        $stmt = DB::conn()->prepare('SELECT distinct betalingswijze FROM members');
        $stmt->execute([]);
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getMembersWithValidEmail() : ?array
    {
        $stmt = DB::conn()->query('SELECT * FROM members WHERE emailadres IS NOT NULL ORDER BY id');
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getMemberCountByYear(int $year) : int
    {
        $stmt = DB::conn()->prepare('SELECT id FROM members WHERE jaarlidmaatschap = ?');
        $stmt->execute([$year]);
        return $stmt->rowCount();
    }

    public static function getYears() : array
    {
        $stmt = DB::conn()->query('SELECT distinct jaarlidmaatschap FROM members order by jaarlidmaatschap desc');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function doesMemberIdExist(int $memberId) : bool
    {
        $stmt = DB::conn()->prepare('SELECT id FROM members WHERE id = ? LIMIT 1');
        $stmt->execute([$memberId]);
        return ($stmt->rowCount() === 1);
    }

    public static function doesEmailforYearExist(int $jaarlidmaatschap, string $email) : bool
    {
        $stmt = DB::conn()->prepare('SELECT id FROM members WHERE jaarlidmaatschap = ? AND emailadres = ? LIMIT 1');
        $stmt->execute([$jaarlidmaatschap, $email]);
        return ($stmt->rowCount() === 1);
    }

    public static function getMemberById(int $id) : ?object
    {
        $stmt = DB::conn()->prepare('SELECT * FROM members WHERE id=? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1) ? $stmt->fetch(PDO::FETCH_OBJ) : null;
    }

    public static function delete(int $id) : bool
    {
        $stmt = DB::conn()->prepare('DELETE FROM members WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1);
    }

    public static function updateMember(Member $member = null) : bool
    {
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
            [
                $member->jaarlidmaatschap, $member->voorletters, $member->voornaam, $member->achternaam,
                $member->geboortedatum, $member->memberAddress->adres, $member->memberAddress->postcode, $member->memberAddress->huisnummer,
                $member->memberAddress->woonplaats, $member->memberContactDetails->telefoon_vast, $member->memberContactDetails->telefoon_mobiel,
                $member->memberContactDetails->emailadres, $member->ingangsdatum, $member->geslacht, $member->memberPreferences->nieuwsbrief,
                $member->memberPreferences->vrijwilliger, $member->memberPreferences->vrijwilligeroptie1, $member->memberPreferences->vrijwilligeroptie2,
                $member->memberPreferences->vrijwilligeroptie3, $member->memberPreferences->vrijwilligeroptie4, $member->memberPreferences->vrijwilligeroptie5,
                $member->paymentDetails->betalingswijze,
                $member->paymentDetails->iban,
                $member->paymentDetails->machtigingskenmerk,
                $member->paymentDetails->status,
                $member->id
            ]
        );
        return ($stmt->rowCount() === 1);
    }

    public static function new(Member $member) : bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO members
                        (
                            id, jaarlidmaatschap, voorletters, voornaam, achternaam, geboortedatum,
                            adres, postcode, huisnummer, woonplaats, telefoon_vast, telefoon_mobiel,
                            emailadres, ingangsdatum, geslacht, nieuwsbrief, vrijwilliger, vrijwilligeroptie1,
                            vrijwilligeroptie2, vrijwilligeroptie3, vrijwilligeroptie4, vrijwilligeroptie5, betalingswijze, iban, machtigingskenmerk, status
                        ) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        return $stmt->execute(
            [
                $member->jaarlidmaatschap, $member->voorletters, $member->voornaam, $member->achternaam, $member->geboortedatum,
                $member->memberAddress->adres, $member->memberAddress->postcode, $member->memberAddress->huisnummer, $member->memberAddress->woonplaats, $member->memberContactDetails->telefoon_vast, $member->memberContactDetails->telefoon_mobiel,
                $member->memberContactDetails->emailadres, $member->ingangsdatum, $member->geslacht, $member->memberPreferences->nieuwsbrief, $member->memberPreferences->vrijwilliger, $member->memberPreferences->vrijwilligeroptie1,
                $member->memberPreferences->vrijwilligeroptie2, $member->memberPreferences->vrijwilligeroptie3, $member->memberPreferences->vrijwilligeroptie4, $member->memberPreferences->vrijwilligeroptie5, $member->paymentDetails->betalingswijze, $member->paymentDetails->iban, $member->paymentDetails->machtigingskenmerk, $member->paymentDetails->status
            ]
        );
    }
}
