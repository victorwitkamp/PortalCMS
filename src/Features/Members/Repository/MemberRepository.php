<?php

declare(strict_types=1);

namespace PortalCMS\Features\Members\Repository;

use Doctrine\ORM\EntityRepository;
use PortalCMS\Features\Members\Entity\Member;

/**
 * @extends EntityRepository<Member>
 */
final class MemberRepository extends EntityRepository
{
    /**
     * Read projections deliberately use DBAL so legacy list templates receive
     * scalar dates and integer TINYINT values rather than ORM-converted types.
     *
     * @return object[]
     */
    public function findRows(?int $year = null, ?string $paymentType = null): array
    {
        $conditions = [];
        $parameters = [];
        if ($year !== null) {
            $conditions[] = 'jaarlidmaatschap = ?';
            $parameters[] = $year;
        }
        if ($paymentType !== null && $paymentType !== '') {
            $conditions[] = 'betalingswijze = ?';
            $parameters[] = $paymentType;
        }

        $sql = 'SELECT * FROM members';
        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY id';

        return array_map(
            static fn (array $row): object => (object) $row,
            $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $parameters),
        );
    }

    /**
     * @return object[]
     */
    public function findRowsWithEmail(): array
    {
        return array_map(
            static fn (array $row): object => (object) $row,
            $this->getEntityManager()->getConnection()->fetchAllAssociative(
                'SELECT * FROM members WHERE emailadres IS NOT NULL ORDER BY id'
            ),
        );
    }

    /**
     * @return int[]
     */
    public function findYears(): array
    {
        return array_map(
            static fn (mixed $year): int => (int) $year,
            $this->getEntityManager()->getConnection()->fetchFirstColumn(
                'SELECT DISTINCT jaarlidmaatschap FROM members ORDER BY jaarlidmaatschap DESC'
            ),
        );
    }

    /**
     * @return string[]
     */
    public function findPaymentTypes(): array
    {
        return array_values(array_filter(
            $this->getEntityManager()->getConnection()->fetchFirstColumn(
                'SELECT DISTINCT betalingswijze FROM members ORDER BY betalingswijze'
            ),
            static fn (mixed $value): bool => is_string($value) && $value !== '',
        ));
    }

    public function countByYear(int $year): int
    {
        return $this->count([ 'jaarlidmaatschap' => $year ]);
    }

    public function emailExistsForYear(int $year, string $email): bool
    {
        return $this->getEntityManager()->getConnection()->fetchOne(
            'SELECT id FROM members WHERE jaarlidmaatschap = ? AND emailadres = ? LIMIT 1',
            [ $year, $email ],
        ) !== false;
    }

    public function save(Member $member): void
    {
        $this->getEntityManager()->persist($member);
    }

    public function remove(Member $member): void
    {
        $this->getEntityManager()->remove($member);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
