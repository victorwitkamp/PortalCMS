<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings\Input;

final readonly class SiteSettingsInput
{
    public function __construct(
        public ?string $site_name = null,
        public ?string $site_description = null,
        public ?string $site_description_type = null,
        public ?string $site_url = null,
        public ?string $site_logo = null,
        public ?string $site_theme = null,
        public ?string $site_layout = null,
        public ?string $WidgetComingEvents = null,
        public ?string $WidgetDebug = null,
        public ?string $MailServer = null,
        public ?string $MailServerPort = null,
        public ?string $MailServerSecure = null,
        public ?string $MailServerAuth = null,
        public ?string $MailServerUsername = null,
        public ?string $MailServerPassword = null,
        public ?string $MailServerDebug = null,
        public ?string $MailFromName = null,
        public ?string $MailFromEmail = null,
        public ?string $MailIsHTML = null,
    ) {
    }

    /**
     * @return array<string, string|null>
     */
    public function values(): array
    {
        return get_object_vars($this);
    }
}
