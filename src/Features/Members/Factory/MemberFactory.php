<?php

declare(strict_types=1);

namespace PortalCMS\Features\Members\Factory;

use PortalCMS\Features\Members\Entity\Member;
use PortalCMS\Features\Members\Input\MemberInput;

final class MemberFactory
{
    public function create(MemberInput $input): Member
    {
        $member = new Member();
        $this->update($member, $input);

        return $member;
    }

    public function update(Member $member, MemberInput $input): void
    {
        foreach (get_object_vars($input) as $property => $value) {
            $member->{$property} = $value;
        }
    }

    public function copyForYear(Member $source, int $year): Member
    {
        $copy = new Member();
        foreach ([
            'voorletters',
            'voornaam',
            'achternaam',
            'geboortedatum',
            'adres',
            'postcode',
            'huisnummer',
            'woonplaats',
            'telefoon_vast',
            'telefoon_mobiel',
            'emailadres',
            'ingangsdatum',
            'geslacht',
            'nieuwsbrief',
            'vrijwilliger',
            'vrijwilligeroptie1',
            'vrijwilligeroptie2',
            'vrijwilligeroptie3',
            'vrijwilligeroptie4',
            'vrijwilligeroptie5',
            'betalingswijze',
            'iban',
            'machtigingskenmerk',
            'opmerking',
        ] as $property) {
            $copy->{$property} = $source->{$property};
        }
        $copy->jaarlidmaatschap = $year;
        $copy->status = 0;

        return $copy;
    }
}
