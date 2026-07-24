<?php

declare(strict_types=1);

namespace PortalCMS\Core\Controller;

use PortalCMS\Core\Http\InvalidInputException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

final class ErrorController extends AbstractController
{
    #[Route('/Error/Error', name: 'error.not_found', methods: [ 'GET', 'POST' ])]
    #[Route('/Error/NotFound', name: 'error.not_found_legacy', methods: [ 'GET', 'POST' ])]
    public function notFound(): Response
    {
        return $this->render(
            'View::Error/ErrorPage',
            [ 'title' => '404 - Not found', 'message' => 'The requested page cannot be found' ],
            Response::HTTP_NOT_FOUND,
        );
    }

    #[Route('/Error/PermissionError', name: 'error.forbidden', methods: [ 'GET', 'POST' ])]
    public function permissionError(): Response
    {
        return $this->render(
            'View::Error/ErrorPage',
            [ 'title' => '403 - Forbidden', 'message' => 'You are not authorized to perform this action.' ],
            Response::HTTP_FORBIDDEN,
        );
    }

    public function exception(Throwable $throwable): Response
    {
        $status = match (true) {
            $throwable instanceof InvalidInputException => Response::HTTP_UNPROCESSABLE_ENTITY,
            $throwable instanceof HttpExceptionInterface => $throwable->getStatusCode(),
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        return $this->render(
            'View::Error/ErrorPage',
            [
                'title' => match ($status) {
                    Response::HTTP_UNAUTHORIZED => '401 - Unauthorized',
                    Response::HTTP_NOT_FOUND => '404 - Not found',
                    Response::HTTP_FORBIDDEN => '403 - Forbidden',
                    Response::HTTP_METHOD_NOT_ALLOWED => '405 - Method not allowed',
                    Response::HTTP_UNPROCESSABLE_ENTITY => '422 - Invalid input',
                    default => '500 - Server error',
                },
                'message' => match ($status) {
                    Response::HTTP_UNAUTHORIZED => 'Authentication is required.',
                    Response::HTTP_NOT_FOUND => 'The requested page cannot be found',
                    Response::HTTP_FORBIDDEN => 'You are not authorized to perform this action.',
                    Response::HTTP_METHOD_NOT_ALLOWED => 'The request method is not allowed for this page.',
                    Response::HTTP_UNPROCESSABLE_ENTITY => 'The submitted data is invalid.',
                    default => 'The request could not be completed.',
                },
            ],
            $status,
        );
    }
}
