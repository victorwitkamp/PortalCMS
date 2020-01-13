<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Activity;

use PortalCMS\Core\Activity\ActivityMapper;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOReader;

/**
 * Class : Activity (Activity.php)
 * Details :
 */
class Activity
{
    public static function load()
    {
        return ActivityMapper::load();
    }

    public static function add(string $activity, int $user_id = null, $user_name = null, $details = null)
    {
        if (!empty($activity)) {
            $ip = self::getVisitorIP();
            ActivityMapper::add($activity, $user_id, $user_name, $ip, $details);
        }
    }

    public static function getVisitorIP()
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']);
        return $ip;
    }

    //    public static function registerUserActivity($activity, $details = null)
    //    {
    //        if (!empty(Session::get('user_id'))) {
    //            $user_id = Session::get('user_id');
    //        }
    //        self::saveUserActivity(null, null, $activity, $details);
    //    }
    //
    //    public static function registerUserActivityByUserId($user_id, $activity, $details)
    //    {
    //        $user = UserPDOReader::getProfileById($user_id);
    //        $user_name = $user['user_name'];
    //        self::saveUserActivity($user_id, $user_name, $activity, $details);
    //    }
    //
    //    public static function registerUserActivityByUsername($user_name, $activity = null, $details = null)
    //    {
    //        $user = UserPDOReader::getByUsername($user_name);
    //        $user_id = $user['user_id'];
    //        self::saveUserActivity($user_id, $user_name, $activity, $details);
    //    }
    //
    //    public static function saveUserActivity($user_id = null, $user_name = null, $activity = null, $details = null)
    //    {
    //        $ip = self::getVisitorIP();
    //        self::saveUserActivityAction($user_id, $user_name, $ip, $activity, $details);
    //    }
    //

}
