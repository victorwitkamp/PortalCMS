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

    public function sitesettings()
    {
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Settings/sitesettings/index');
    }

    public function Users()
    {
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Settings/Users/index');
    }

    public function Profile()
    {
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Settings/Profile/index');
    }

    public function Roles()
    {
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Settings/Roles/index');
    }

    public function Role()
    {
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Settings/Role/index');
    }

    public static function saveSiteSettings()
    {
        if (SiteSetting::saveSiteSettings()) {
            Session::add('feedback_positive', 'Instellingen succesvol opgeslagen.');
            Redirect::to('settings/sitesettings');
        } else {
            Session::add('feedback_negative', 'Fout bij opslaan van instellingen.');
            Redirect::to('settings/sitesettings');
        }
    }

    public static function uploadLogo()
    {
        if (SiteSetting::uploadLogo()) {
            Session::add('feedback_positive', Text::get('FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL'));
            Redirect::to('home');
        } else {
            Redirect::to('settings/logo');
        }
    }
}
