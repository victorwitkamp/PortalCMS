<?php


declare(strict_types=1);

namespace App\Controller;

use App\Core\Security\Authentication\Authentication;
use App\Core\User\UserMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Profile")
 */
class ProfileController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();
    }

    /**
     * @Route("/", name="")
     */
    public function index(Request $request): Response
    {
        $user = UserMapper::getProfileById((int) $this->request->get('id'));
        if (!empty($user)) {
            return $this->render('Profile', (array) $user);
        } else {
            //            header('HTTP/1.0 404 Not Found', true, 404);
            //            return $this->render('Pages/Error/Error', [ 'title' => '404 - Not found', 'message' => 'The requested page cannot be found' ]);
            return $this->redirectToRoute('errorpermissionerror');
        }
    }
}
