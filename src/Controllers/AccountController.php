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
use PortalCMS\Core\User\UserPDOWriter;
use PortalCMS\Core\View\Text;

/**
 * AccountController
 */
class AccountController extends Controller
{
    /**
     * The requests that this controller will handle
     * @var array $requests
     */
    private $requests = [
        'changeUsername' => 'POST',
        'changepassword' => 'POST',
        'clearUserFbid' => 'POST'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    public function index()
    {
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Account/Index');
    }

    public static function changeUsername()
    {
        User::editUsername(Request::post('user_name'));
    }

    public static function changepassword()
    {
        $username = (string) Session::get('user_name');
        $current = (string) Request::post('currentpassword');
        $new = (string) Request::post('newpassword');
        $confirm = (string) Request::post('newconfirmpassword');
        Password::changePassword(
            $username,
            $current,
            $new,
            $confirm
        );
    }

    public static function clearUserFbid()
    {
        if (UserPDOWriter::updateFBid((int) Session::get('user_id'))) {
            Session::set('user_fbid', null);
            Session::add('feedback_positive', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_SUCCESS'));
            Redirect::to('Account');
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_FAILED'));
        Redirect::to('Account');
    }

    public static function setFbid(int $userId, int $FbId)
    {
        if (!empty($FbId) && UserPDOWriter::updateFBid($userId, $FbId)) {
            Session::set('user_fbid', $FbId);
            Session::add('feedback_positive', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_SUCCESS'));
            Redirect::to('Account');
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_FAILED'));
            Redirect::to('Account');
        }
    }
}
