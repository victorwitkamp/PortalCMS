<?php
class SiteSettingController extends Controller
{
    public function __construct() {
        parent::__construct();

        if (isset($_POST['saveSiteSettings'])) {
            if (SiteSetting::saveSiteSettings()) {
                Session::add('feedback_positive', "Instellingen succesvol opgeslagen.");
                Redirect::to("settings/site-settings/index.php");
            } else {
                Session::add('feedback_negative', "Fout bij opslaan van instellingen.");
                Redirect::to("settings/site-settings/index.php");
            }
        }
        if (isset($_POST['uploadLogo'])) {
            SiteSetting::uploadLogo_action();
        }
    }
}