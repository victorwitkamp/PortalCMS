<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Users\Authentication\Authentication;
use PortalCMS\Features\Users\Entity\User;
use PortalCMS\Features\Users\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ProfileController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly UserRepository $users,
        private readonly Authentication $authentication,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Profile', name: 'profile.index', methods: [ 'GET' ])]
    #[Route('/Profile/', name: 'profile.index_slash', methods: [ 'GET' ])]
    public function index(Request $request): Response
    {
        if (!$this->authentication->isLoggedIn()) {
            return $this->redirectToRoute('login.index', [
                'redirect' => $request->getRequestUri(),
            ]);
        }
        $user = $this->users->find($request->query->getInt('id'));
        if (!$user instanceof User) {
            return $this->notFoundResponse();
        }

        return $this->render('Users::Profile/UserProfilePage', [ 'user' => $user ]);
    }
}
