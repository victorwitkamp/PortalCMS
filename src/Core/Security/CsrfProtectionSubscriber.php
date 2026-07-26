<?php

declare(strict_types=1);

namespace PortalCMS\Core\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final readonly class CsrfProtectionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private CsrfTokenManagerInterface $tokens,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if ($request->isMethodSafe()) {
            return;
        }

        $route = $request->attributes->getString('_route');
        if ($route === '') {
            return;
        }

        $submittedToken = $request->headers->get('X-CSRF-Token');
        if (!is_string($submittedToken) || $submittedToken === '') {
            $submittedToken = $request->request->getString('_csrf_token');
        }

        if (!$this->tokens->isTokenValid(new CsrfToken($route, $submittedToken))) {
            throw new AccessDeniedHttpException('Invalid CSRF token.');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [ 'onKernelRequest', 8 ],
        ];
    }
}
