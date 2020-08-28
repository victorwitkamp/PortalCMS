<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use League\Plates\Engine;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\User\Password;
use PortalCMS\Core\User\User;
use PortalCMS\Core\User\UserMapper;
use PortalCMS\Core\View\Text;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AccountController
 * @package PortalCMS\Controllers
 */
class AccountController
{
    protected $templates;

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public function changeUsername() : ResponseInterface
    {
        User::editUsername((string) Request::post('user_name'));
        return new RedirectResponse('/Account');
    }

    public function changePassword() : ResponseInterface
    {
        Password::changePassword(
            UserMapper::getByUsername(
                (string) Session::get('user_name')
            ),
            (string) Request::post('currentpassword'),
            (string) Request::post('newpassword'),
            (string) Request::post('newconfirmpassword')
        );
        return new RedirectResponse('/Account');
    }

    public function clearUserFbid() : ResponseInterface
    {
        if (UserMapper::updateFBid((int) Session::get('user_id'))) {
            Session::set('user_fbid', null);
            Session::add('feedback_positive', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_SUCCESS'));
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_FAILED'));
        }
        return new RedirectResponse('/Account');
    }

    public static function setFbid(int $user_id, int $FbId = null) : ResponseInterface
    {
        if ($FbId !== null && UserMapper::updateFBid($user_id, $FbId)) {
            Session::set('user_fbid', $FbId);
            Session::add('feedback_positive', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_SUCCESS'));
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_FAILED'));
        }
        return new RedirectResponse('/Account');
    }

    public function index() : ResponseInterface
    {
        return new HtmlResponse($this->templates->render('Pages/Account/Index'));
    }
}
