<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Config\SiteSetting;
use App\Core\HTTP\Redirect;
use App\Core\HTTP\Request;
use App\Core\Security\Authentication\Authentication;
use App\Core\Security\Authorization\Authorization;
use App\Core\View\Text;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Settings", name="settings")
 */
class SettingsController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();
    }

    /**
     * @Route("/General",name="general")
     */
    public function siteSettings() : Response
    {
        if (Authorization::hasPermission('site-settings')) {
            return $this->render('Settings/General.html.twig');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Activity",name="activity")
     */
    public function activity()
    {
        if (Authorization::hasPermission('recent-activity')) {
            return $this->render('Settings/Activity.html.twig');
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }

    /**
     * @Route("/Logo",name="logo")
     */
    public function logo()
    {
        if (Authorization::hasPermission('site-settings')) {
            return $this->render('Settings/Logo.html.twig');
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }

    /**
     * @Route("/Debug",name="debug")
     */
    public function debug()
    {
        if (Authorization::hasPermission('debug')) {
            return $this->render('Settings/Debug.html.twig');
        } else {
            return $this->redirectToRoute('errorpermissionerror');
        }
    }

    public function saveSiteSettings()
    {
        if (SiteSetting::saveSiteSettings()) {
            $this->addFlash('success','Instellingen succesvol opgeslagen.');
            $this->redirectToRoute('/Settings/SiteSettings');
        } else {
            $this->addFlash('danger','Fout bij opslaan van instellingen.');
            $this->redirectToRoute('/Settings/SiteSettings');
        }
    }

    public function uploadLogo()
    {
        $logo = Request::files('logo_file');
        if (SiteSetting::processUploadedLogo($logo)) {
            $this->addFlash('success',Text::get('FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL'));
            $this->redirectToRoute('/Home');
        } else {
            $this->redirectToRoute('/Settings/Logo');
        }
    }


}
