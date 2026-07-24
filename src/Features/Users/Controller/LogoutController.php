<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Users\Authentication\Authentication;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class LogoutController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly Authentication $authentication,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Logout', name: 'logout', methods: [ 'GET', 'POST' ])]
    #[Route('/Logout/', name: 'logout_slash', methods: [ 'GET', 'POST' ])]
    public function index(): Response
    {
        $this->authentication->logout();
        $response = $this->redirectToRoute('login.index');
        $cookie = $this->authentication->takeResponseCookie();
        if ($cookie !== null) {
            $response->headers->setCookie($cookie);
        }

        return $response;
    }
}
