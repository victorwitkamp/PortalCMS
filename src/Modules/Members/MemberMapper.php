<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Members;

use PDO;
use PortalCMS\Core\Database\Database;

/**
 * Class MemberMapper
 * @package PortalCMS\Modules\Members
 */
class MemberMapper
{
    /**
     * @param int|null    $year
     * @param string|null $paymentType
     * @return array|null
     */
    public static function getMembers(int $year = null, string $paymentType = null): ?array
    {
        if (!empty($year) && !empty($paymentType)) {
            return self::getMembersByYearAndPaymentType($year, $paymentType);
        }
        if (!empty($year) && empty($paymentType)) {
            return self::getMembersByYear($year);
        }
        if (empty($year) && !empty($paymentType)) {
            return self::getMembersByPaymentType($paymentType);
        }
        $stmt = Database::conn()->query('SELECT * FROM members ORDER BY id');
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int $year
     * @return array|null
     */
    public static function getMembersByYear(int $year): ?array
    {
        $stmt = Database::conn()->prepare(
            'SELECT * FROM members WHERE jaarlidmaatschap = ? ORDER BY id'
        );
        $stmt->execute([
            $year
        ]);
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $paymentType
     * @return array|null
     */
    public static function getMembersByPaymentType(string $paymentType): ?array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM members where betalingswijze = ? ORDER BY id');
        $stmt->execute([ $paymentType ]);
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int    $year
     * @param string $paymentType
     * @return array|null
     */
    public static function getMembersByYearAndPaymentType(int $year, string $paymentType): ?array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM members where jaarlidmaatschap = ? and betalingswijze = ? ORDER BY id');
        $stmt->execute([ $year, $paymentType ]);
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @return array|null
     */
    public static function getPaymentTypes(): ?array
    {
        $stmt = Database::conn()->prepare('SELECT distinct betalingswijze FROM members');
        $stmt->execute([]);
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @return array|null
     */
    public static function getMembersWithValidEmail(): ?array
    {
        $stmt = Database::conn()->query('SELECT * FROM members WHERE emailadres IS NOT NULL ORDER BY id');
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int $year
     * @return int
     */
    public static function getMemberCountByYear(int $year): int
    {
        $stmt = Database::conn()->prepare('SELECT id FROM members WHERE jaarlidmaatschap = ?');
        $stmt->execute([ $year ]);
        return $stmt->rowCount();
    }

    /**
     * @return array
     */
    public static function getYears(): array
    {
        $stmt = Database::conn()->query('SELECT distinct jaarlidmaatschap FROM members order by jaarlidmaatschap desc');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @param int $memberId
     * @return bool
     */
    /**
     * @param int $memberId
     * @return bool
     */
    /**
     * @param int $memberId
     * @return bool
     */
    public static function doesMemberIdExist(int $memberId): bool
    {
        $stmt = Database::conn()->prepare('SELECT id FROM members WHERE id = ? LIMIT 1');
        $stmt->execute([ $memberId ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int    $jaarlidmaatschap
     * @param string $email
     * @return bool
     */
    /**
     * @param int    $jaarlidmaatschap
     * @param string $email
     * @return bool
     */
    /**
     * @param int    $jaarlidmaatschap
     * @param string $email
     * @return bool
     */
    public static function doesEmailforYearExist(int $jaarlidmaatschap, string $email): bool
    {
        $stmt = Database::conn()->prepare('SELECT id FROM members WHERE jaarlidmaatschap = ? AND emailadres = ? LIMIT 1');
        $stmt->execute([ $jaarlidmaatschap, $email ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int $id
     * @return object|null
     */
    /**
     * @param int $id
     * @return object|null
     */
    /**
     * @param int $id
     * @return object|null
     */
    public static function getMemberById(int $id): ?object
    {
        $stmt = Database::conn()->prepare('SELECT * FROM members WHERE id=? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1) ? $stmt->fetch(PDO::FETCH_OBJ) : null;
    }

    /**
     * @param int $id
     * @return bool
     */
    /**
     * @param int $id
     * @return bool
     */
    /**
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = Database::conn()->prepare('DELETE FROM members WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param Member|null $member
     * @return bool
     */
    /**
     * @param Member|null $member
     * @return bool
     */
    /**
     * @param Member|null $member
     * @return bool
     */
    public static function updateMember(Member $member = null): bool
    {
        $stmt = Database::conn()->prepare('UPDATE members
                SET jaarlidmaatschap=?, voorletters=?, voornaam=?, achternaam=?,
                geboortedatum=?, adres=?, postcode=?, huisnummer=?,
                woonplaats=?, telefoon_vast=?, telefoon_mobiel=?,
                emailadres=?, ingangsdatum=?, geslacht=?, nieuwsbrief=?,
                vrijwilliger=?, vrijwilligeroptie1=?, vrijwilligeroptie2=?,
                vrijwilligeroptie3=?, vrijwilligeroptie4=?, vrijwilligeroptie5=?,
                betalingswijze=?, iban=?, machtigingskenmerk=?, status=? WHERE id=?');
        $stmt->execute([
                $member->jaarlidmaatschap, $member->voorletters, $member->voornaam, $member->achternaam, $member->geboortedatum, $member->address->adres, $member->address->postcode, $member->address->huisnummer, $member->address->woonplaats, $member->contactDetails->telefoon_vast, $member->contactDetails->telefoon_mobiel, $member->contactDetails->emailadres, $member->ingangsdatum, $member->geslacht, $member->preferences->nieuwsbrief, $member->preferences->vrijwilliger, $member->preferences->vrijwilligeroptie1, $member->preferences->vrijwilligeroptie2, $member->preferences->vrijwilligeroptie3, $member->preferences->vrijwilligeroptie4, $member->preferences->vrijwilligeroptie5, $member->paymentDetails->betalingswijze, $member->paymentDetails->iban, $member->paymentDetails->machtigingskenmerk, $member->paymentDetails->status, $member->id
            ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int|null $id
     * @param int|null $status
     * @return bool
     */
    /**
     * @param int|null $id
     * @param int|null $status
     * @return bool
     */
    /**
     * @param int|null $id
     * @param int|null $status
     * @return bool
     */
    public static function setStatus(int $id = null, int $status = null) : bool
    {
        $stmt = Database::conn()->prepare(
            'UPDATE members SET status=? WHERE id=?'
        );
        $stmt->execute([$status, $id]);
        return ($stmt->rowCount() === 1);
    }

    public static function new(Member $member): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO members
                        (
                            id, jaarlidmaatschap, voorletters, voornaam, achternaam, geboortedatum,
                            adres, postcode, huisnummer, woonplaats, telefoon_vast, telefoon_mobiel,
                            emailadres, ingangsdatum, geslacht, nieuwsbrief, vrijwilliger, vrijwilligeroptie1,
                            vrijwilligeroptie2, vrijwilligeroptie3, vrijwilligeroptie4, vrijwilligeroptie5, betalingswijze, iban, machtigingskenmerk, status
                        ) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
                $member->jaarlidmaatschap, $member->voorletters, $member->voornaam, $member->achternaam, $member->geboortedatum, $member->address->adres, $member->address->postcode, $member->address->huisnummer, $member->address->woonplaats, $member->contactDetails->telefoon_vast, $member->contactDetails->telefoon_mobiel, $member->contactDetails->emailadres, $member->ingangsdatum, $member->geslacht, $member->preferences->nieuwsbrief, $member->preferences->vrijwilliger, $member->preferences->vrijwilligeroptie1, $member->preferences->vrijwilligeroptie2, $member->preferences->vrijwilligeroptie3, $member->preferences->vrijwilligeroptie4, $member->preferences->vrijwilligeroptie5, $member->paymentDetails->betalingswijze, $member->paymentDetails->iban, $member->paymentDetails->machtigingskenmerk, $member->paymentDetails->status
            ]);
    }
}
