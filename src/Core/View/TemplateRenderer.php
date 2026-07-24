<?php

declare(strict_types=1);

namespace PortalCMS\Core\View;

use League\Plates\Engine;
use PortalCMS\Features\Settings\SiteSetting;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class TemplateRenderer
{
    private Engine $engine;

    private ?Request $contextRequest = null;

    private bool $hasRequestData = false;

    /** @var array<string, mixed> */
    private array $requestData = [];

    public function __construct(
        private readonly SiteSetting $settings,
        private readonly RequestStack $requestStack,
    ) {
        $this->engine = new Engine();
        $this->engine->addFolder('View', dirname(__DIR__, 2) . '/View');

        $featureViews = glob(dirname(__DIR__, 2) . '/Features/*/View/Templates', GLOB_ONLYDIR);
        if ($featureViews === false) {
            throw new RuntimeException('Feature view directories could not be discovered.');
        }
        foreach ($featureViews as $featureView) {
            $featureName = basename(dirname(dirname($featureView)));
            $this->engine->addFolder($featureName, $featureView);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = []): string
    {
        $this->engine->addData($this->sharedData());

        return $this->engine->render($template, $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function response(string $template, array $data = [], int $status = Response::HTTP_OK): Response
    {
        return new Response($this->render($template, $data), $status);
    }

    /** @return array<string, mixed> */
    private function sharedData(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($this->hasRequestData && $this->contextRequest === $request) {
            return $this->requestData;
        }

        $currentUserName = null;
        $flashMessages = [];
        if (
            $request instanceof Request
            && $request->hasSession()
            && ($request->hasPreviousSession() || $request->getSession()->isStarted())
        ) {
            $session = $request->getSession();
            $currentUserName = $session->get('user_name');
            $flashMessages = $session->getFlashBag()->all();
        }

        $this->contextRequest = $request;
        $this->hasRequestData = true;
        $this->requestData = [
            'siteName' => $this->settings->get('site_name') ?? 'PortalCMS',
            'siteTheme' => $this->settings->get('site_theme') ?? 'default',
            'currentUserName' => is_string($currentUserName) ? $currentUserName : null,
            'flashMessages' => $flashMessages,
        ];

        return $this->requestData;
    }
}
