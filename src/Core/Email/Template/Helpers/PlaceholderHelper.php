<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Core\Email\Template\Helpers;

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Modules\Members\MemberModel;

/**
 * Class PlaceholderHelper
 * @package PortalCMS\Core\Email\Template\Helpers
 */
class PlaceholderHelper
{
    /**
     */
    public static function replaceMemberPlaceholders(int $memberId, string $text): string
    {
        //        $member = MemberMapper::getMemberById($memberId);
        $member = MemberModel::getMember($memberId);
        $variables = [
            'voornaam' => $member->voornaam, 'achternaam' => $member->achternaam, 'iban' => $member->paymentDetails->iban, 'afzender' => SiteSetting::get('MailFromName')
        ];
        if (!empty($variables)) {
            foreach ($variables as $placeholder => $value) {
                if (!empty($placeholder) && !empty($value)) {
                    $text = self::replace($placeholder, $value, $text);
                }
            }
        }
        return $text;
    }

    /**
     * replace
     * @param $placeholder
     * @param $value
     * @param $text
     * @return string The $text string with the specified placeholder replaced by the specified value.
     */
    public static function replace(string $placeholder, string $value, string $text): string
    {
        return str_replace('{' . strtoupper($placeholder) . '}', $value, $text);
    }
}
