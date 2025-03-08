<?php


declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="homepage")
 */
class DefaultController extends AbstractController
{

    //    public function __construct()
    //    {
    //        Session::init();
    //        if (!Authentication::userIsLoggedIn() && !empty(Request::cookie('remember_me')) && !(new LoginController())->loginWithCookie()) {
    //            return $this->render('Pages/Login/indexNew');
    //        }
    //    }

    /**
     * @Route("", name="")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('login');
    }
}
