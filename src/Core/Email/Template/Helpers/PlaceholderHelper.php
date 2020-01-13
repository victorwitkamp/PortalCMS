<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Core\Email\Template\Helpers;

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Modules\Members\MemberModel;

class PlaceholderHelper
{
    public static function replaceMemberPlaceholders(int $memberId, string $text)
    {
        $member = MemberModel::getMemberById($memberId);
        $variables = [
            'voornaam' => $member->voornaam,
            'achternaam' => $member->achternaam,
            'iban' => $member->iban,
            'afzender' => SiteSetting::getStaticSiteSetting('MailFromName')
        ];
        if (!empty($variables)) {
            foreach ($variables as $placeholder => $value) {
                $text = self::replace($placeholder, $value, $text);
            }
        }
        return $text;
    }

    /**
     * replace
     *
     * @param $placeholder
     * @param $value
     * @param $text
     * @return string The $text string with the specified placeholder replaced by the specified value.
     */
    public static function replace(string $placeholder, string $value, string $text)
    {
        return str_replace('{' . strtoupper($placeholder) . '}', $value, $text);
    }
}
