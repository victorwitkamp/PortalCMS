<?php

declare(strict_types=1);

namespace PortalCMS\Features\Activity\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Activity\Activity;
use PortalCMS\Features\Users\Authorization\Authorization;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ActivityController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly Activity $activity,
        private readonly Authorization $authorization,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Activity', name: 'activity.index', methods: [ 'GET' ])]
    public function index(): Response
    {
        return $this->authorization->hasPermission('recent-activity')
            ? $this->render('@Activity/ActivityLogPage.html.twig', [ 'activities' => $this->activity->load() ])
            : $this->forbiddenResponse();
    }
}
