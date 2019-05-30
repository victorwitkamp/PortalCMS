<?php
class POSTController
{
    public function __construct()
    {
        if (isset($_POST['saveMember'])) {
            Member::saveMember();
        }
        if (isset($_POST['saveNewMember'])) {
            Member::newMember();
        }
        if (isset($_POST['saveNewProduct'])) {
            Product::new();
        }
        if (isset($_POST['saveNewInvoice'])) {
            Invoice::new();
        }
        if (isset($_POST['deleteinvoiceitem'])) {
            Invoice::deleteInvoiceItem();
        }
        if (isset($_POST['addinvoiceitem'])) {
            Invoice::addInvoiceItem();
        }
        if (isset($_POST['updatePage'])) {
            Page::updatePage($_POST['id'], $_POST['content']);
        }
        if (isset($_POST['saveSiteSettings'])) {
            if (SiteSetting::saveSiteSettings()) {
                Session::add('feedback_positive', "Instellingen succesvol opgeslagen.");
                Redirect::redirectPage("settings/site-settings/index.php");
            } else {
                Session::add('feedback_negative', "Fout bij opslaan van instellingen.");
                Redirect::redirectPage("settings/site-settings/index.php");
            }
        }
    }
}