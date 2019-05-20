<?php

/**
 * Class : SiteSettings (SiteSettings.class.php)
 * Details : SiteSettings.
 */

class SiteSettings
{
    public $SiteName;
    public $SiteDescription;
    public $SiteURL;
    public $SiteLogo;
    public $SiteTheme;
    public $SiteLayout;
    
    public $WidgetComingEvents;

    public function getSiteSettings() 
    {
        $this->SiteName = $this->getSiteSetting('site_name');
        $this->SiteDescription = $this->getSiteSetting('site_description');
        $this->SiteTheme = $this->getSiteSetting('site_theme');
        $this->SiteLayout = $this->getSiteSetting('site_layout');
        $this->SiteURL = $this->getSiteSetting('site_url');
        $this->SiteLogo = $this->getSiteSetting('site_logo');
        $this->WidgetComingEvents = $this->getSiteSetting('WidgetComingEvents');
    }

    public static function saveSiteSettings()
    {
        self::setSiteSetting(Request::post('site_name'), 'site_name');
        self::setSiteSetting(Request::post('site_description'), 'site_description');
        self::setSiteSetting(Request::post('site_url'), 'site_url');
        self::setSiteSetting(Request::post('site_logo'), 'site_logo');
        self::setSiteSetting(Request::post('site_theme'), 'site_theme');
        self::setSiteSetting(Request::post('site_layout'), 'site_layout');
        self::setSiteSetting(Request::post('WidgetComingEvents'), 'WidgetComingEvents');
        self::setSiteSetting(Request::post('site_description_type'), 'site_description_type');
        return true;
    }

    static function setSiteSetting($value, $setting)
    {
        $stmt = DB::conn()->prepare("UPDATE site_settings SET string_value = ? WHERE setting = ?");
        if (!$stmt->execute([$value, $setting])) {
            return false;
        } else {
            return true;
        }
    }

    function getSiteSetting($setting)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM site_settings WHERE setting = ?");
        $stmt->execute([$setting]);
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return $row['string_value'];
        }
    }

    public static function getStaticSiteSetting($setting)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM site_settings WHERE setting = ?");
        $stmt->execute([$setting]);
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return $row['string_value'];
        }
    }



}