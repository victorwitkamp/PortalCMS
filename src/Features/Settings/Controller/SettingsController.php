<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\Http\RequestInputMapper;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Core\View\Text;
use PortalCMS\Features\Activity\Activity;
use PortalCMS\Features\Settings\Input\SiteSettingsInput;
use PortalCMS\Features\Settings\SiteSetting;
use PortalCMS\Features\Users\Authorization\Authorization;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SettingsController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly RequestInputMapper $inputMapper,
        private readonly SiteSetting $settings,
        private readonly Activity $activity,
        private readonly Authorization $authorization,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Settings/SiteSettings', name: 'settings.index', methods: [ 'GET' ])]
    #[Route('/Settings/SiteSettings/', name: 'settings.index_slash', methods: [ 'GET' ])]
    public function siteSettings(): Response
    {
        return $this->authorization->hasPermission('site-settings')
            ? $this->render('Settings::SiteSettingsPage', [
                'settings' => $this->settings->values(),
            ])
            : $this->forbiddenResponse();
    }

    #[Route('/Settings/SiteSettings', name: 'settings.save', methods: [ 'POST' ])]
    #[Route('/Settings/SiteSettings/', name: 'settings.save_slash', methods: [ 'POST' ])]
    public function save(Request $request): Response
    {
        if (!$this->authorization->hasPermission('site-settings')) {
            return $this->forbiddenResponse();
        }

        /** @var SiteSettingsInput $input */
        $input = $this->inputMapper->map($request, SiteSettingsInput::class);
        if ($this->settings->save($input->values())) {
            $this->addFlash('success', 'Instellingen succesvol opgeslagen.');
        } else {
            $this->addFlash('danger', 'Niet alle instellingen konden worden opgeslagen.');
        }

        return $this->redirectToRoute('settings.index');
    }

    #[Route('/Settings/Activity', name: 'settings.activity', methods: [ 'GET' ])]
    #[Route('/Settings/Activity/', name: 'settings.activity_slash', methods: [ 'GET' ])]
    public function activity(): Response
    {
        return $this->authorization->hasPermission('recent-activity')
            ? $this->render('Settings::ActivityLogPage', [ 'activities' => $this->activity->load() ])
            : $this->forbiddenResponse();
    }

    #[Route('/Settings/Logo', name: 'settings.logo', methods: [ 'GET' ])]
    #[Route('/Settings/Logo/', name: 'settings.logo_slash', methods: [ 'GET' ])]
    public function logo(): Response
    {
        return $this->authorization->hasPermission('site-settings')
            ? $this->render('Settings::LogoSettingsPage')
            : $this->forbiddenResponse();
    }

    #[Route('/Settings/Logo', name: 'settings.logo_upload', methods: [ 'POST' ])]
    #[Route('/Settings/Logo/', name: 'settings.logo_upload_slash', methods: [ 'POST' ])]
    public function uploadLogo(Request $request): Response
    {
        if (!$this->authorization->hasPermission('site-settings')) {
            return $this->forbiddenResponse();
        }

        if ($this->settings->uploadLogo($request->files->get('logo_file'))) {
            $this->addFlash('success', Text::get('FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL'));
            return $this->redirectToRoute('home.index');
        }
        $this->addFlash(
            'danger',
            $this->settings->error() ?? (string) Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'),
        );

        return $this->redirectToRoute('settings.logo');
    }

    #[Route('/Settings/Debug', name: 'settings.debug', methods: [ 'GET' ])]
    #[Route('/Settings/Debug/', name: 'settings.debug_slash', methods: [ 'GET' ])]
    public function debug(): Response
    {
        if (!$this->authorization->hasPermission('debug')) {
            return $this->forbiddenResponse();
        }

        $session = $this->session();

        return $this->render('Settings::DebugInformationPage', [
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
