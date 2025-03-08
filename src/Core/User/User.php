<?php


declare(strict_types=1);

namespace App\Core\User;

use App\Core\HTTP\Redirect;
use App\Core\Session\Session;
use App\Core\View\Text;

class User
{
    public static function editUsername(string $newUsername): bool
    {
        if ($newUsername === Session::get('user_name')) {
            $this->addFlash('danger',Text::get('FEEDBACK_USERNAME_SAME_AS_OLD_ONE'));
        } elseif (!preg_match('/^[a-zA-Z0-9]{2,64}$/', $newUsername)) {
            $this->addFlash('danger',Text::get('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
        } else {
            $username = substr(strip_tags($newUsername), 0, 64);
            if (UserMapper::usernameExists($username)) {
                $this->addFlash('danger',Text::get('FEEDBACK_USERNAME_ALREADY_TAKEN'));
            } elseif (!UserMapper::updateUsername((int)Session::get('user_id'), $username)) {
                $this->addFlash('danger',Text::get('FEEDBACK_UNKNOWN_ERROR'));
            } else {
                Session::set('user_name', $username);
                $this->addFlash('success',Text::get('FEEDBACK_USERNAME_CHANGE_SUCCESSFUL'));
                Redirect::to('Account');
                return true;
            }
        }
        return false;
    }
}
