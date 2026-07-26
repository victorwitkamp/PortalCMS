<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\Http\RequestInputMapper;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Settings\Application\Settings;
use PortalCMS\Features\Settings\Input\UpdateSettingsInput;
use PortalCMS\Features\Users\Authorization\Authorization;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use RuntimeException;

final class SettingsController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly RequestInputMapper $inputMapper,
        private readonly Settings $settings,
        private readonly Authorization $authorization,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Settings', name: 'settings.index', methods: [ 'GET' ])]
    public function index(): Response
    {
        return $this->authorization->hasPermission('site-settings')
            ? $this->render('@Settings/SettingsPage.html.twig', [
                'settings' => $this->settings->editableValues(),
            ])
            : $this->forbiddenResponse();
    }

    #[Route('/Settings', name: 'settings.update', methods: [ 'POST' ])]
    public function update(Request $request): Response
    {
        if (!$this->authorization->hasPermission('site-settings')) {
            return $this->forbiddenResponse();
        }
        /** @var UpdateSettingsInput $input */
        $input = $this->inputMapper->map($request, UpdateSettingsInput::class);
        try {
            $this->settings->update($input);
            $this->addFlash('success', 'Instellingen succesvol opgeslagen.');
        } catch (RuntimeException) {
            $this->addFlash('danger', 'De instellingen konden niet worden opgeslagen.');
        }

        return $this->redirectToRoute('settings.index');
    }

    #[Route('/Settings/Logo', name: 'settings.logo', methods: [ 'GET' ])]
    public function logo(): Response
    {
        return $this->authorization->hasPermission('site-settings')
            ? $this->render('@Settings/LogoPage.html.twig')
            : $this->forbiddenResponse();
    }

    #[Route('/Settings/Logo', name: 'settings.logo_update', methods: [ 'POST' ])]
    public function updateLogo(Request $request): Response
    {
        if (!$this->authorization->hasPermission('site-settings')) {
            return $this->forbiddenResponse();
        }
        try {
            $this->settings->replaceLogo($request->files->get('logo_file'));
            $this->addFlash('success', 'Logo succesvol opgeslagen.');
            return $this->redirectToRoute('home.index');
        } catch (RuntimeException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->redirectToRoute('settings.logo');
    }
}
