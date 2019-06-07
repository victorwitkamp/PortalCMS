<?php

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

    public static function clearFbid() {
        if (User::updateFbid(Session::get('user_id'), NULL)) {
            Session::set('user_fbid', NULL);
            Session::add('feedback_positive', Text::get("FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_SUCCESS"));
            Redirect::myAccount();
        }
        Session::add('feedback_negative', Text::get("FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_FAILED"));
        Redirect::myAccount();
    }
    public static function setFbid($FbId) {
        if (!empty($FbId)) {
            if (User::updateFbid(Session::get('user_id'), $FbId)) {
                Session::set('user_fbid', $FbId);
                Session::add('feedback_positive', Text::get("FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_SUCCESS"));
                Redirect::to('my-account/index.php');
            } else {
                Session::add('feedback_negative', Text::get("FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_FAILED"));
                Redirect::to('my-account/index.php');
            }
        } else {
            Session::add('feedback_negative', Text::get("FEEDBACK_CONNECT_FACEBOOK_ACCOUNT_FAILED"));
            Redirect::to('my-account/index.php');
        }
    }
}