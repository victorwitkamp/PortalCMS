<?php

/**
 * Class : Activity (Activity.php)
 * Details :
 */
class Activity
{
    public static function load() {
        $stmt = DB::conn()->prepare("SELECT * FROM activity ORDER BY id desc LIMIT 50");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getVisitorIP()
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']);
        return $ip;
    }

    public static function registerUserActivity($activity, $details = null)
    {
        if (!empty(Session::get('user_id'))) {
            $user_id = Session::get('user_id');
        }
        self::saveUserActivity(null, null, $activity, $details);
    }

    public static function registerUserActivityByUserId($user_id, $activity, $details)
    {
        $user = User::getProfileById($user_id);
        $user_name = $user['user_name'];
        self::saveUserActivity($user_id, $user_name, $activity, $details);
    }

    public static function registerUserActivityByUsername($user_name, $activity = null, $details = null)
    {
        $user = User::getByUsername($user_name);
        $user_id = $user['user_id'];
        self::saveUserActivity($user_id, $user_name, $activity, $details);
    }

    public static function saveUserActivity($user_id = null, $user_name = null, $activity = null, $details = null)
    {
        $ip = self::getVisitorIP();
        self::saveUserActivityAction($user_id, $user_name, $ip, $activity, $details);
    }

    public static function saveUserActivityAction($user_id = null, $user_name = null, $ip = null, $activity = null, $details = null) {
        $sql = 'INSERT INTO activity (id, user_id, user_name, ip_address, activity, details) VALUES (NULL, ?, ?, ?, ?, ?)';
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute([$user_id, $user_name, $ip, $activity, $details]);
    }
}