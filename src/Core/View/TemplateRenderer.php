<?php

declare(strict_types=1);

namespace PortalCMS\Core\View;

use PortalCMS\Features\Settings\Application\Settings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class TemplateRenderer
{
    private ?Request $contextRequest = null;

    private bool $hasRequestData = false;

    /** @var array<string, mixed> */
    private array $requestData = [];

    public function __construct(
        private readonly Environment $twig,
        private readonly Settings $settings,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = []): string
    {
        return $this->twig->render($template, [
            ...$this->sharedData(),
            ...$data,
        ]);
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
            'siteName' => $this->settings->value('site_name') ?? 'PortalCMS',
            'siteTheme' => $this->settings->value('site_theme') ?? 'default',
            'currentUserName' => is_string($currentUserName) ? $currentUserName : null,
            'flashMessages' => $flashMessages,
        ];

        return $this->requestData;
    }
}
