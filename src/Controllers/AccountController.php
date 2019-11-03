<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\User\User;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\User\Password;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOWriter;
use PortalCMS\Core\Controllers\Controller;

/**
 * AccountController
 * Controls everything that is account-related
 */
class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['changeUsername'])) {
            User::editUsername(Request::post('user_name'));
        }
        if (isset($_POST['changepassword'])) {
            Password::changePassword(
                Session::get('user_name'),
                Request::post('currentpassword'),
                Request::post('newpassword'),
                Request::post('newconfirmpassword')
            );
        }
        if (isset($_POST['clearUserFbid'])) {
            self::clearFbid();
        }
    }

    public static function clearFbid()
    {
        if (UserPDOWriter::updateFBid(Session::get('user_id'), null)) {
            Session::set('user_fbid', null);
            Session::add('feedback_positive', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_SUCCESS'));
            Redirect::to('my-account');
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_FAILED'));
        Redirect::to('my-account');
    }
    public static function setFbid($FbId)
    {
        if (!empty($FbId)) {
            if (UserPDOWriter::updateFBid(Session::get('user_id'), $FbId)) {
                Session::set('user_fbid', $FbId);
                Session::add('feedback_positive', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_SUCCESS'));
                Redirect::to('my-account');
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_FAILED'));
                Redirect::to('my-account');
            }
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_FAILED'));
            Redirect::to('my-account');
        }
    }
}
