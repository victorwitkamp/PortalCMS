<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

class SettingsController extends Controller
{
    /**
     * The requests that this controller will handle
     * @var array $requests
     */
    private $requests = [
        'saveSiteSettings' => 'POST',
        'uploadLogo' => 'POST'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        Router::processRequests($this->requests, __CLASS__);
    }

    /**
     * Route: /Settings/SiteSettings
     */
    public function siteSettings()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('site-settings');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Settings/SiteSettings/index');
    }

    /**
     * Route: /Settings/Activity
     */
    public function activity()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('recent-activity');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Settings/Activity/index');
    }

    /**
     * Route: /Settings/Logo
     */
    public function logo()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('site-settings');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Settings/Logo/index');
    }

    /**
     * Route: /Settings/Debug
     */
    public function debug()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('debug');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Settings/Debug/index');
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
        if (SiteSetting::uploadLogo()) {
            Session::add('feedback_positive', Text::get('FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL'));
            Redirect::to('home');
        } else {
            Redirect::to('Settings/Logo');
        }
    }
}
