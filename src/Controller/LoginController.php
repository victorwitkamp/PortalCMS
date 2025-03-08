<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Config\SiteSetting;
use App\Core\Security\Csrf;
use App\Core\HTTP\Cookie;
use App\Core\Security\Authentication\Authentication;
use App\Core\Security\Authentication\Service\LoginService;
use App\Core\User\PasswordReset;
use App\Core\View\Text;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Login", name="login")
 */
class LoginController extends AbstractController
{

    public function __construct()
    {
        // if (isset($_POST['signupSubmit'])) {
        //     $this->signup($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm_password']);
        // }
        // if (isset($_POST['activateSubmit'])) {
        //     if ($this->activate($_POST['email'], $_POST['code'])) {
        //         Redirect::to('Login');
        //     }
        // }
    }

    /**
     * @Route("", name="")
     */
    public function index(Request $request) : Response
    {
        if ($request->isMethod('POST')) {
            if ($request->get('set_remember_me_cookie') === 'on') {
                $remember = true;
            } else {
                $remember = false;
            }
            return $this->loginSubmit($request->get('user_name'),$request->get('user_password'), $remember);
        }
        if (Authentication::userIsLoggedIn()) {
            $this->addFlash('success','You are already logged in.');
//            if ($this->request->get('redirect')) {
//                Redirect::to(ltrim(urldecode($this->request->get('redirect')), '/'));
//            } else {
                return $this->redirectToRoute('home');
//            }
        }
        $cookie = $request->cookies->get('remember_me');
        if (!empty($cookie) && $this->loginWithCookie($cookie)) {
            $this->addFlash('success','You are automatically logged in using a cookie.');
//            if ($this->request->get('redirect')) {
//                Redirect::to(ltrim(urldecode($this->request->get('redirect')), '/'));
//            } else {
                return $this->redirectToRoute('home');
//            }
        }
        return $this->render('Login.html.twig', [
            'page_title' =>  'Inloggen - ' . SiteSetting::get('site_name'),
            'csrf_token' => Csrf::makeToken()
        ]);
    }

    public function loginSubmit(string $username, string $password, $remember = false) : Response
    {
//        if (!Csrf::isTokenValid()) {
//            $this->addFlash('danger','Invalid CSRF token.');
//            return $this->redirectToRoute('login');
//        }
        $rememberMe = false;
        if ($remember) {
            $rememberMe = true;
        }
        $loginService = new LoginService();
        if (!empty($username) && !empty($password) && $loginService->loginWithPassword($username, $password, $rememberMe)) {
//            if (!empty($this->request->get('redirect'))) {
//                Redirect::to(ltrim(urldecode($this->request->get('redirect')), '/'));
//            } else {
                return $this->redirectToRoute('home');
//            }
        }
        return $this->redirectToRoute('login');
    }

    public function loginWithFacebook(int $fbid) : Response
    {
        if (LoginService::loginWithFacebook($fbid)) {
//            if ($this->request->get('redirect')) {
//                Redirect::to(ltrim(urldecode($this->request->get('redirect')), '/'));
//            } else {
              return $this->redirectToRoute('home');
//            }
        }
//        if ($this->request->get('redirect')) {
//            Redirect::to('Login/?redirect=' . ltrim(urlencode($this->request->get('redirect')), '/'));
//        } else {
        return $this->redirectToRoute('login');
//        }
    }

    public function requestPasswordResetSubmit() : Response
    {
        PasswordReset::requestPasswordReset(
            (string)$this->request->get('user_name_or_email')
        );
        return $this->redirectToRoute('login');
    }

    public function resetSubmit() : Response
    {
        $username = (string)$this->request->get('username');
        $resetHash = (string)$this->request->get('password_reset_hash');
        if (PasswordReset::verifyPasswordReset($username, $resetHash)) {
            $passwordHash = password_hash(base64_encode((string)$this->request->get('password')), PASSWORD_DEFAULT);
            if (PasswordReset::saveNewUserPassword($username, $passwordHash, $resetHash)) {
                $this->addFlash('success',Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL'));
                return $this->redirectToRoute('login');
            }
            $this->addFlash('danger',Text::get('FEEDBACK_PASSWORD_CHANGE_FAILED'));
        }
        return $this->redirectToRoute('loginpasswordreset');
    }

    public function loginWithCookie(string $cookie): bool
    {
        if (!empty($cookie) && LoginService::loginWithCookie((string)$cookie)) {
            return true;
        }
        // if not, delete cookie (outdated? attack?) and route user to login form to prevent infinite login loops
        Cookie::delete();
        return false;
    }

    public function requestPasswordReset()
    {

        return $this->render('Pages/Login/RequestPasswordReset');
    }

    /**
     * @Route("passwordreset", name="passwordreset")
     */
    public function passwordReset()
    {

        return $this->render('Pages/Login/PasswordReset');
    }

    public function activate()
    {

        return $this->render('Pages/Login/Activate');
    }
}
