<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Core\Email\Template\Helpers;

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Modules\Members\MemberModel;

class PlaceholderHelper
{
    public static function replace($key, $value, $text)
    {
        return str_replace('{' . strtoupper($key) . '}', $value, $text);
    }

    public static function replaceholdersMember($memberId, $text)
    {
        $member = MemberModel::getMemberById($memberId);
        $variables = [
            'voornaam' => $member->voornaam,
            'achternaam' => $member->achternaam,
            'iban' => $member->iban,
            'afzender' => SiteSetting::getStaticSiteSetting('MailFromName')
        ];
        foreach ($variables as $key => $value) {
            $text = self::replace($key, $value, $text);
        }
        return $text;
    }

    public static function replaceholder($placeholder, $placeholdervalue, $body_in)
    {
        $variables = [
            $placeholder=>$placeholdervalue
        ];
        foreach ($variables as $key => $value) {
            $body_out = str_replace('{' . strtoupper($key) . '}', $value, $body_in);
        }
        return $body_out;
    }
}
