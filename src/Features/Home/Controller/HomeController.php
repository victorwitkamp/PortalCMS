<?php

declare(strict_types=1);

namespace PortalCMS\Features\Home\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Home\Service\HomeService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class HomeController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly HomeService $home,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/', name: 'home.root', methods: [ 'GET' ])]
    #[Route('/Home', name: 'home.index', methods: [ 'GET' ])]
    #[Route('/Home/', name: 'home.index_slash', methods: [ 'GET' ])]
    public function index(): Response
    {
        return $this->render('Home::HomePage', $this->home->data());
    }
}
