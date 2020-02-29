<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Modules\Members;

use PDO;
use PortalCMS\Core\Database\DB;

class MemberMapper
{
    public static function getMembers() : ?array
    {
        $stmt = DB::conn()
            ->query('SELECT * FROM members ORDER BY id');
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getMembersByYear(int $year) : ?array
    {
        $stmt = DB::conn()
            ->prepare('SELECT * FROM members where jaarlidmaatschap = ? ORDER BY id')
            ->execute([$year]);
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getMembersWithValidEmail() : ?array
    {
        $stmt = DB::conn()
            ->query('SELECT * FROM members WHERE emailadres IS NOT NULL ORDER BY id');
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getMemberCountByYear(int $year) : int
    {
        $stmt = DB::conn()
            ->prepare('SELECT id FROM members WHERE jaarlidmaatschap = ?')
            ->execute([$year]);
        return $stmt->rowCount();
    }

    public static function getYears() : array
    {
        $stmt = DB::conn()
            ->query('SELECT distinct jaarlidmaatschap FROM members');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function doesMemberIdExist(int $memberId) : bool
    {
        $stmt = DB::conn()
            ->prepare('SELECT id FROM members WHERE id = ? LIMIT 1')
            ->execute([$memberId]);
        return ($stmt->rowCount() === 1);
    }

    public static function doesEmailforYearExist(int $jaarlidmaatschap, string $email) : bool
    {
        $stmt = DB::conn()
            ->prepare('SELECT id FROM members WHERE jaarlidmaatschap = ? AND emailadres = ? LIMIT 1')
            ->execute([$jaarlidmaatschap, $email]);
        return ($stmt->rowCount() === 1);
    }

    public static function getMemberById(int $id) : ?object
    {
        $stmt = DB::conn()
            ->prepare('SELECT * FROM members WHERE id=? LIMIT 1')
            ->execute([$id]);
        return ($stmt->rowCount() === 1) ? $stmt->fetch(PDO::FETCH_OBJ) : null;
    }

    public static function delete(int $id) : bool
    {
        $stmt = DB::conn()
            ->prepare('DELETE FROM members WHERE id = ? LIMIT 1')
            ->execute([$id]);
        return ($stmt->rowCount() === 1);
    }
}
