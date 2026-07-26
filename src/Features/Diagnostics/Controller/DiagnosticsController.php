<?php

declare(strict_types=1);

namespace PortalCMS\Features\Diagnostics\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Users\Authorization\Authorization;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class DiagnosticsController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly Authorization $authorization,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Diagnostics', name: 'diagnostics.index', methods: [ 'GET' ])]
    public function index(): Response
    {
        if (!$this->authorization->hasPermission('debug')) {
            return $this->forbiddenResponse();
        }

        $session = $this->session();

        return $this->render('@Diagnostics/DiagnosticsPage.html.twig', [
            'temporaryDirectory' => sys_get_temp_dir() . DIRECTORY_SEPARATOR,
            'sessionContext' => [
                'user_id' => (int) $session->get('user_id'),
                'user_name' => $session->get('user_name'),
                'user_email' => $session->get('user_email'),
                'user_fbid' => $session->get('user_fbid'),
                'user_logged_in' => $session->get('user_logged_in') === true,
                'failed_login_count' => (int) $session->get('failed-login-count'),
                'last_failed_login' => (int) $session->get('last-failed-login'),
            ],
        ]);
    }
}
