<?php

declare(strict_types=1);

namespace PortalCMS\Core\Http;

use PortalCMS\Core\Controller\ErrorController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionListener implements EventSubscriberInterface
{
    public function __construct(private readonly ErrorController $errors)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        if (
            !$throwable instanceof InvalidInputException
            && (
                !$throwable instanceof HttpExceptionInterface
                || $throwable->getStatusCode() >= 500
            )
        ) {
            error_log((string) $throwable);
        }
        $event->setResponse($this->errors->exception($throwable));
    }

    public static function getSubscribedEvents(): array
    {
        return [ KernelEvents::EXCEPTION => [ 'onKernelException', -96 ] ];
    }
}
