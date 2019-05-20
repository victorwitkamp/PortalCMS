<?php

/**
 * Class : UserActivity (UserActivity.php)
 * Details : 
 */
class UserActivity
{
    public static function load() {
        $stmt = DB::conn()->prepare("SELECT * FROM user_activity ORDER BY activity_id desc LIMIT 50");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function getVisitorIP() 
    {
        $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
        return $ip;
    }
    public static function registerUserActivity($activity)
    {
        $user_id = Session::get('user_id');
        $user_name = Session::get('user_name');
        self::saveUserActivity($user_id, $user_name, $activity);
    }

    public static function registerUserActivityByUserId($user_id, $activity)
    {
        $user = User::getProfileById($user_id);
        $user_name = $user['user_name']; 
        self::saveUserActivity($user_id, $user_name, $activity);
    }
    public static function registerUserActivityByUsername($user_name, $activity)
    {
        $user_id = User::getUserIdByUsername($user_name);
        self::saveUserActivity($user_id, $user_name, $activity);
    }
    public static function saveUserActivity($user_id, $user_name, $activity)
    {
        $ip = self::getVisitorIP();
        $sql = 'INSERT INTO user_activity (activity_id, user_id, user_name, ip_address, activity) VALUES (NULL, ?, ?, ?, ?)';
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute([$user_id, $user_name, $ip, $activity]);
    }
   
}