<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use League\Plates\Engine;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\View\Text;
use Psr\Http\Message\ResponseInterface;

/**
 * Class SettingsController
 * @package PortalCMS\Controllers
 */
class SettingsController
{
    protected $templates;

    private $requests = [
        'saveSiteSettings' => 'POST', 'uploadLogo' => 'POST'
    ];

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public static function saveSiteSettings() : ResponseInterface
    {
        if (SiteSetting::saveSiteSettings()) {
            Session::add('feedback_positive', 'Instellingen succesvol opgeslagen.');
            return new RedirectResponse('/Settings/SiteSettings');
        }
        Session::add('feedback_negative', 'Fout bij opslaan van instellingen.');
        return new RedirectResponse('/Settings/SiteSettings');
    }

    public static function uploadLogo() : ResponseInterface
    {
        if (SiteSetting::uploadLogo()) {
            Session::add('feedback_positive', Text::get('FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL'));
            return new RedirectResponse('/Home');
        }
        return new RedirectResponse('/Settings/Logo');
    }

    public function siteSettings() : ResponseInterface
    {
        if (Authorization::hasPermission('site-settings')) {
            return new HtmlResponse($this->templates->render('Pages/Settings/SiteSettings'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function activity() : ResponseInterface
    {
        if (Authorization::hasPermission('recent-activity')) {
            return new HtmlResponse($this->templates->render('Pages/Settings/Activity'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function logo() : ResponseInterface
    {
        if (Authorization::hasPermission('site-settings')) {
            return new HtmlResponse($this->templates->render('Pages/Settings/Logo'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function debug() : ResponseInterface
    {
        if (Authorization::hasPermission('debug')) {
            return new HtmlResponse($this->templates->render('Pages/Settings/Debug'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }
}
