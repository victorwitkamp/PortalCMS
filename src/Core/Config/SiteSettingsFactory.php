<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Core\Config;

use PortalCMS\Core\HTTP\Request;

/**
 * Class SiteSettingsFactory
 * @package PortalCMS\Core\Config
 */
class SiteSettingsFactory
{
    /**
     */
    public static function updateRequest(): array
    {
        $properties = [
            'site_name', 'site_description', 'site_description_type', 'site_url', 'site_logo', 'site_theme', 'site_layout', 'WidgetComingEvents', 'WidgetDebug', 'MailServer', 'MailServerPort', 'MailServerSecure', 'MailServerAuth', 'MailServerUsername', 'MailServerPassword', 'MailServerDebug', 'MailFromName', 'MailFromEmail', 'MailIsHTML'
        ];
        $settings = [];
        foreach ($properties as $property) {
            $settings[$property] = (string)Request::post($property);
        }
        return $settings;
    }
}
