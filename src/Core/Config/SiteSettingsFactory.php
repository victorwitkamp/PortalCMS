<?php
declare(strict_types=1);


namespace App\Core\Config;

class SiteSettingsFactory
{
    public static function updateRequest(): array
    {
        $properties = [
            'site_name', 'site_description', 'site_description_type', 'site_url', 'site_logo', 'site_theme', 'site_layout', 'WidgetComingEvents', 'WidgetDebug', 'MailServer', 'MailServerPort', 'MailServerSecure', 'MailServerAuth', 'MailServerUsername', 'MailServerPassword', 'MailServerDebug', 'MailFromName', 'MailFromEmail', 'MailIsHTML'
        ];
        $settings = [];
        foreach ($properties as $property) {
            $settings[$property] = (string)$this->request->get($property);
        }
        return $settings;
    }
}
