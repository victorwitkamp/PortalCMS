<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings\Application;

use Doctrine\ORM\EntityManagerInterface;
use PortalCMS\Features\Settings\Entity\Setting;
use PortalCMS\Features\Settings\Input\UpdateSettingsInput;
use PortalCMS\Features\Settings\Repository\SettingRepository;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class Settings
{
    public const EDITABLE_NAMES = [
        'site_name',
        'site_description',
        'site_description_type',
        'site_url',
        'site_logo',
        'site_theme',
        'site_layout',
        'WidgetComingEvents',
        'WidgetDebug',
        'MailServer',
        'MailServerPort',
        'MailServerSecure',
        'MailServerAuth',
        'MailServerUsername',
        'MailServerPassword',
        'MailServerDebug',
        'MailFromName',
        'MailFromEmail',
        'MailIsHTML',
    ];

    public function __construct(
        private readonly SettingRepository $settings,
        private readonly EntityManagerInterface $entityManager,
        private readonly SiteLogo $siteLogo,
    ) {
    }

    public function value(string $name): ?string
    {
        return $this->settings->findByName($name)?->value();
    }

    /**
     * @param string[] $names
     * @return array<string, string|null>
     */
    public function values(array $names): array
    {
        return $this->settings->findValues($names);
    }

    /**
     * @return array<string, string|null>
     */
    public function editableValues(): array
    {
        $values = $this->values(self::EDITABLE_NAMES);
        $values['MailServerPassword'] = null;

        return $values;
    }

    public function update(UpdateSettingsInput $input): void
    {
        $values = $input->values();
        $settings = $this->settings->findByNames(array_keys($values));

        foreach (array_keys($values) as $name) {
            if (!isset($settings[$name])) {
                throw new RuntimeException(sprintf('Setting "%s" does not exist.', $name));
            }
        }

        foreach ($values as $name => $value) {
            $settings[$name]->changeValue($value);
        }

        $this->entityManager->flush();
    }

    public function replaceLogo(?UploadedFile $file): void
    {
        $setting = $this->settings->findByName('site_logo');
        if (!$setting instanceof Setting) {
            throw new RuntimeException('Setting "site_logo" does not exist.');
        }

        $publicPath = $this->siteLogo->replace($file);
        $setting->changeValue($publicPath);
        $this->entityManager->flush();
    }
}
