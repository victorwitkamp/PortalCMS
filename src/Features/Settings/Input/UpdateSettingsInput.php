<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings\Input;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateSettingsInput
{
    public function __construct(
        #[Assert\Length(max: 64)]
        public ?string $siteName = null,
        #[Assert\Length(max: 64)]
        public ?string $siteDescription = null,
        #[Assert\Choice(choices: [ '1', '2', '3' ])]
        public ?string $siteDescriptionType = null,
        #[Assert\Length(max: 64)]
        public ?string $siteUrl = null,
        #[Assert\Length(max: 64)]
        public ?string $siteLogo = null,
        #[Assert\Choice(choices: [ 'darkly', 'solar', 'superhero', 'cyborg', 'flatly', 'slate' ])]
        public ?string $siteTheme = null,
        #[Assert\Choice(choices: [ 'right-sidebar', 'left-sidebar' ])]
        public ?string $siteLayout = null,
        #[Assert\Choice(choices: [ 'true', 'false' ])]
        public ?string $widgetComingEvents = null,
        #[Assert\Choice(choices: [ 'true', 'false' ])]
        public ?string $widgetDebug = null,
        #[Assert\Length(max: 64)]
        public ?string $mailServer = null,
        #[Assert\Regex(pattern: '/^\d{1,5}$/')]
        public ?string $mailServerPort = null,
        #[Assert\Choice(choices: [ 'tls', 'ssl', 'off' ])]
        public ?string $mailServerSecure = null,
        #[Assert\Choice(choices: [ 'true', 'false' ])]
        public ?string $mailServerAuth = null,
        #[Assert\Length(max: 64)]
        public ?string $mailServerUsername = null,
        #[Assert\Length(max: 64)]
        public ?string $mailServerPassword = null,
        #[Assert\Choice(choices: [ '0', '1', '2', '3', '4' ])]
        public ?string $mailServerDebug = null,
        #[Assert\Length(max: 64)]
        public ?string $mailFromName = null,
        #[Assert\Email]
        #[Assert\Length(max: 64)]
        public ?string $mailFromEmail = null,
        #[Assert\Choice(choices: [ 'true', 'false' ])]
        public ?string $mailIsHtml = null,
    ) {
    }

    /**
     * @return array<string, string|null>
     */
    public function values(): array
    {
        $values = [
            'site_name' => $this->siteName,
            'site_description' => $this->siteDescription,
            'site_description_type' => $this->siteDescriptionType,
            'site_url' => $this->siteUrl,
            'site_logo' => $this->siteLogo,
            'site_theme' => $this->siteTheme,
            'site_layout' => $this->siteLayout,
            'WidgetComingEvents' => $this->widgetComingEvents,
            'WidgetDebug' => $this->widgetDebug,
            'MailServer' => $this->mailServer,
            'MailServerPort' => $this->mailServerPort,
            'MailServerSecure' => $this->mailServerSecure,
            'MailServerAuth' => $this->mailServerAuth,
            'MailServerUsername' => $this->mailServerUsername,
            'MailServerDebug' => $this->mailServerDebug,
            'MailFromName' => $this->mailFromName,
            'MailFromEmail' => $this->mailFromEmail,
            'MailIsHTML' => $this->mailIsHtml,
        ];
        if ($this->mailServerPassword !== null) {
            $values['MailServerPassword'] = $this->mailServerPassword;
        }

        return $values;
    }
}
