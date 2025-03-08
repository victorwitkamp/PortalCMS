<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Config\SiteSetting;
use App\Core\Security\Authentication\Authentication;
use App\Core\Session\Session;
use App\Core\View\PageMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();
    }

    /**
     * @Route("/Home", name="home")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $pageLoader = new PageMapper();
        $page = $pageLoader->getPage(1);
        return $this->render('Home.html.twig', [
            'page_title'   => 'Home - ' . SiteSetting::get('site_name'),
            'site_logo'    => SiteSetting::get('site_logo'),
            'site_name'    => SiteSetting::get('site_name'),
            'site_year'    => date('Y'),
            'user_name'    => Session::get('user_name'),
            'page_content' => $page['content']
        ]);
    }
}
