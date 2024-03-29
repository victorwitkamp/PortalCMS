<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\Password;
use PortalCMS\Core\User\User;
use PortalCMS\Core\User\UserMapper;
use PortalCMS\Core\View\Text;

class AccountController extends Controller
{
    private $requests = [
        'changeUsername' => 'POST', 'changePassword' => 'POST', 'clearUserFbid' => 'POST'
    ];

    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    public static function changeUsername()
    {
        User::editUsername((string)Request::post('user_name'));
    }

    public static function changePassword()
    {
        Password::changePassword(UserMapper::getByUsername((string)Session::get('user_name')), (string)Request::post('currentpassword'), (string)Request::post('newpassword'), (string)Request::post('newconfirmpassword'));
    }

    public static function clearUserFbid()
    {
        if (UserMapper::updateFBid((int)Session::get('user_id'))) {
            Session::set('user_fbid', null);
            Session::add('feedback_positive', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_SUCCESS'));
            Redirect::to('Account');
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_FAILED'));
        Redirect::to('Account');
    }

    public static function setFbid(int $user_id, int $FbId = null)
    {
        if (!empty($FbId) && UserMapper::updateFBid($user_id, $FbId)) {
            Session::set('user_fbid', $FbId);
            Session::add('feedback_positive', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_SUCCESS'));
            Redirect::to('Account');
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_FAILED'));
            Redirect::to('Account');
        }
    }

    public function index()
    {
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Account/Index');
    }
}
