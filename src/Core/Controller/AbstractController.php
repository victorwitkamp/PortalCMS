<?php

declare(strict_types=1);

namespace PortalCMS\Core\Controller;

use LogicException;
use PortalCMS\Core\View\TemplateRenderer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractController
{
    public function __construct(
        protected readonly TemplateRenderer $templates,
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $template, array $data = [], int $status = Response::HTTP_OK): Response
    {
        return $this->templates->response($template, $data, $status);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    protected function redirectToRoute(
        string $route,
        array $parameters = [],
        int $status = Response::HTTP_FOUND,
    ): RedirectResponse {
        return new RedirectResponse(
            $this->urlGenerator->generate($route, $parameters),
            $status,
        );
    }

    protected function redirectToLocalPath(
        string $path,
        int $status = Response::HTTP_FOUND,
    ): RedirectResponse {
        $baseUrl = $this->requestStack->getCurrentRequest()?->getBaseUrl() ?? '';
        $url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

        return new RedirectResponse($url, $status);
    }

    protected function session(): SessionInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null || !$request->hasSession()) {
            throw new LogicException('No Symfony session is available for the current request.');
        }

        return $request->getSession();
    }

    protected function addFlash(string $type, mixed $message): void
    {
        $this->session()->getFlashBag()->add($type, $message);
    }

    protected function notFoundResponse(string $message = 'The requested page cannot be found'): Response
    {
        return $this->render(
            'View::Error/ErrorPage',
            [ 'title' => '404 - Not found', 'message' => $message ],
            Response::HTTP_NOT_FOUND,
        );
    }

    protected function forbiddenResponse(string $message = 'You are not authorized to perform this action.'): Response
    {
        return $this->render(
            'View::Error/ErrorPage',
            [ 'title' => '403 - Forbidden', 'message' => $message ],
            Response::HTTP_FORBIDDEN,
        );
    }
}
