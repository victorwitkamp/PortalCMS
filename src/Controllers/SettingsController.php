<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

/**
 * Class SettingsController
 * @package PortalCMS\Controllers
 */
class SettingsController extends Controller
{
    /**
     * The requests that this controller will handle
     * @var array $requests
     */
    private $requests = [
        'saveSiteSettings' => 'POST', 'uploadLogo' => 'POST'
    ];

    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    public static function saveSiteSettings()
    {
        if (SiteSetting::saveSiteSettings()) {
            Session::add('feedback_positive', 'Instellingen succesvol opgeslagen.');
            Redirect::to('Settings/SiteSettings');
        } else {
            Session::add('feedback_negative', 'Fout bij opslaan van instellingen.');
            Redirect::to('Settings/SiteSettings');
        }
    }

    public static function uploadLogo()
    {
        Authentication::checkAuthentication();
        if (SiteSetting::uploadLogo()) {
            Session::add('feedback_positive', Text::get('FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL'));
            Redirect::to('Home');
        } else {
            Redirect::to('Settings/Logo');
        }
    }

    public function siteSettings() : void
    {
        if (Authorization::hasPermission('site-settings')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Settings/SiteSettings');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function activity() : void
    {
        if (Authorization::hasPermission('recent-activity')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Settings/Activity');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function logo() : void
    {
        if (Authorization::hasPermission('site-settings')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Settings/Logo');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function debug() : void
    {
        if (Authorization::hasPermission('debug')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Settings/Debug');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }
}
