<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

class SiteSettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['saveSiteSettings'])) {
            if (SiteSetting::saveSiteSettings()) {
                Session::add('feedback_positive', 'Instellingen succesvol opgeslagen.');
                Redirect::to('settings/site-settings');
            } else {
                Session::add('feedback_negative', 'Fout bij opslaan van instellingen.');
                Redirect::to('settings/site-settings');
            }
        }
        if (isset($_POST['uploadLogo'])) {
            if (SiteSetting::uploadLogo()) {
                Session::add('feedback_positive', Text::get('FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL'));
                Redirect::to('home');
            } else {
                Redirect::to('settings/logo');
            }
        }
    }
}
