<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Authentication;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class AuthenticationListener implements EventSubscriberInterface
{
    private const PUBLIC_PREFIXES = [ '/Login', '/Error' ];

    public function __construct(
        private readonly Authentication $authentication,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        foreach (self::PUBLIC_PREFIXES as $prefix) {
            if (str_starts_with($request->getPathInfo(), $prefix)) {
                return;
            }
        }
        if ($this->authentication->isLoggedIn()) {
            return;
        }

        $rememberMe = $request->cookies->getString('remember_me');
        if (
            $rememberMe !== ''
            && $this->authentication->loginFromRememberMeCookie($rememberMe)
        ) {
            return;
        }

        $event->setResponse(new RedirectResponse($this->urlGenerator->generate(
            'login.index',
            [ 'redirect' => $request->getRequestUri() ],
        )));
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $cookie = $this->authentication->takeResponseCookie();
        if ($cookie !== null) {
            $event->getResponse()->headers->setCookie($cookie);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [ 'onKernelRequest', 0 ],
            KernelEvents::RESPONSE => [ 'onKernelResponse', 0 ],
        ];
    }
}
