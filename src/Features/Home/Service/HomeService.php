<?php

declare(strict_types=1);

namespace PortalCMS\Features\Home\Service;

use PortalCMS\Features\Events\Repository\EventRepository;
use PortalCMS\Features\Pages\Repository\PageRepository;
use PortalCMS\Features\Settings\SiteSetting;
use PortalCMS\Features\Users\Authorization\Authorization;
use Throwable;

final class HomeService
{
    public function __construct(
        private readonly SiteSetting $settings,
        private readonly PageRepository $pages,
        private readonly EventRepository $events,
        private readonly Authorization $authorization,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        $settings = $this->settings->values([
            'site_logo',
            'site_name',
            'site_layout',
            'site_description',
            'site_description_type',
            'WidgetComingEvents',
            'WidgetDebug',
        ]);

        return [
            'settings' => $settings,
            'description' => $this->description($settings),
            'page' => $this->pages->find('1'),
            'events' => $this->events->findUpcoming(new \DateTimeImmutable()),
            'canEdit' => $this->authorization->hasPermission('site-settings'),
        ];
    }

    /**
     * @param array<string, string|null> $settings
     */
    private function description(array $settings): string
    {
        return match ($settings['site_description_type'] ?? '1') {
            '2' => $this->fetchJoke(
                'https://sv443.net/jokeapi/v2/joke/Dark?format=json',
                static fn (array $payload): ?string => ($payload['type'] ?? null) === 'twopart'
                    ? ($payload['setup'] ?? '') . '<br>' . ($payload['delivery'] ?? '')
                    : ($payload['joke'] ?? null),
            ),
            '3' => $this->fetchJoke(
                'https://api.chucknorris.io/jokes/random?category=dev',
                static fn (array $payload): ?string => $payload['value'] ?? null,
            ),
            default => (string) ($settings['site_description'] ?? ''),
        };
    }

    /**
     * @param callable(array<string, mixed>): ?string $extract
     */
    private function fetchJoke(string $url, callable $extract): string
    {
        $curl = curl_init($url);
        if ($curl === false) {
            return '';
        }
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_HTTPHEADER => [ 'Accept: application/json' ],
        ]);

        try {
            $result = curl_exec($curl);
            if (!is_string($result)) {
                return '';
            }
            $payload = json_decode($result, true, flags: JSON_THROW_ON_ERROR);
            return is_array($payload) ? (string) ($extract($payload) ?? '') : '';
        } catch (Throwable) {
            return '';
        } finally {
            curl_close($curl);
        }
    }
}
