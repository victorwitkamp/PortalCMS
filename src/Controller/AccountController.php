<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Config\SiteSetting;
use App\Core\Security\Authentication\Authentication;
use App\Core\Security\Authorization\UserRoleMapper;
use App\Core\Security\Csrf;
use App\Core\Session\Session;
use App\Core\User\UserMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Account", name="account")
 */
class AccountController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();
    }

    /**
     * @Route("", name="")
     */
    public function index(Request $request): Response
    {
       $user = UserMapper::getProfileById((int) Session::get('user_id'));
    ////require DIR_ROOT . 'login/ext/fb/config.php';
    /**/    //$helper = $fb->getRedirectLoginHelper();
    //$permissions = [ 'email' ];
    //$loginUrl = $helper->getLoginUrl(Config::get('FB_ASSIGN_URL'), $permissions);
        return $this->render('Account.html.twig', [
            'user'       => $user,
            'page_title' => 'TITLE_MY_ACCOUNT',
            'site_name'  => SiteSetting::get('site_name'),
            'site_year'  => date('Y'),
            'user_name'  => Session::get('user_name'),
            'roles'      => UserRoleMapper::getByUserId($user->user_id),
            'csrf_token' => Csrf::makeToken()
        ]);
    }

    //public function changeUsername(): Response
    //{
    //    User::editUsername((string) $this->request->get('user_name'));
    //}
//
//    public function changePassword(): Response
//    {
//        $currentPassword = (string) $this->request->get('currentpassword');
//        $newPassword = (string) $this->request->get('newpassword');
//        $repeatNewPassword = (string) $this->request->get('newconfirmpassword');
//        $user = UserMapper::getByUsername((string) Session::get('user_name'));
//
//        if (empty($currentPassword) || empty($newPassword) || empty($repeatNewPassword)) {
//            $this->addFlash('danger', Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY'));
//        } elseif ($newPassword !== $repeatNewPassword) {
//            $this->addFlash('danger', Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG'));
//        } elseif ($currentPassword === $newPassword) {
//            $this->addFlash('danger', Text::get('FEEDBACK_PASSWORD_NEW_SAME_AS_CURRENT'));
//        } elseif (strlen($newPassword) <= 6) {
//            $this->addFlash('danger', Text::get('FEEDBACK_PASSWORD_TOO_SHORT'));
//        } elseif (!Password::verifyPassword($user, $currentPassword)) {
//            $this->addFlash('danger', Text::get('FEEDBACK_PASSWORD_CURRENT_INCORRECT'));
//        } elseif (UserMapper::updatePassword($user->user_name, password_hash(base64_encode($newPassword), PASSWORD_DEFAULT))) {
//            $this->addFlash('success', Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL'));
//        } else {
//            $this->addFlash('danger', Text::get('FEEDBACK_PASSWORD_CHANGE_FAILED'));
//        }
//    }
//
//    public function clearUserFbid(): Response
//    {
//        if (UserMapper::updateFBid((int) Session::get('user_id'))) {
//            Session::set('user_fbid', null);
//            $this->addFlash('success', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_SUCCESS'));
//            return $this->redirectToRoute('account');
//        }
//        $this->addFlash('danger', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_FAILED'));
//        return $this->redirectToRoute('account');
//    }
//
//    public function setFbid(int $user_id, int $FbId = null): Response
//    {
//        if ($FbId !== null && UserMapper::updateFBid($user_id, $FbId)) {
//            Session::set('user_fbid', $FbId);
//            $this->addFlash('success', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_SUCCESS'));
//        } else {
//            $this->addFlash('danger', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_FAILED'));
//        }
//        return $this->redirectToRoute('account');
//    }
}
