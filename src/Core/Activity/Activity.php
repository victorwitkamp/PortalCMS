<?php

namespace PortalCMS\Core\Activity;

use PortalCMS\Core\Database\DB;

/**
 * Class : Activity (Activity.php)
 * Details :
 */
class Activity
{
    public static function load()
    {
        return DB::conn()->query('SELECT * FROM activity ORDER BY id desc LIMIT 50')->fetchAll();
    }

    // public static function getVisitorIP()
    // {
    //     $ip = $_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']);
    //     return $ip;
    // }

    //    public static function registerUserActivity($activity, $details = NULL)
    //    {
    //        if (!empty(Session::get('user_id'))) {
    //            $user_id = Session::get('user_id');
    //        }
    //        self::saveUserActivity(NULL, NULL, $activity, $details);
    //    }
    //
    //    public static function registerUserActivityByUserId($user_id, $activity, $details)
    //    {
    //        $user = UserMapper::getProfileById($user_id);
    //        $user_name = $user['user_name'];
    //        self::saveUserActivity($user_id, $user_name, $activity, $details);
    //    }
    //
    //    public static function registerUserActivityByUsername($user_name, $activity = NULL, $details = NULL)
    //    {
    //        $user = UserMapper::getByUserName($user_name);
    //        $user_id = $user['user_id'];
    //        self::saveUserActivity($user_id, $user_name, $activity, $details);
    //    }
    //
    //    public static function saveUserActivity($user_id = NULL, $user_name = NULL, $activity = NULL, $details = NULL)
    //    {
    //        $ip = self::getVisitorIP();
    //        self::saveUserActivityAction($user_id, $user_name, $ip, $activity, $details);
    //    }
    //
    //    public static function saveUserActivityAction($user_id = NULL, $user_name = NULL, $ip = NULL, $activity = NULL, $details = NULL)
    //    {
    //        $sql = 'INSERT INTO activity (id, user_id, user_name, ip_address, activity, details) VALUES (NULL, ?, ?, ?, ?, ?)';
    //        $stmt = DB::conn()->prepare($sql);
    //        $stmt->execute([$user_id, $user_name, $ip, $activity, $details]);
    //    }
}
