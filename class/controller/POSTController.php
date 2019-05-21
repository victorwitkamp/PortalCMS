<?php
class POSTController
{
    public function __construct()
    {
        // Login
        if (isset($_POST['loginSubmit'])) {
            LoginController::loginWithPassword();
        }

        // Registration
        if (isset($_POST['signupSubmit'])) {
            $this->signup($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm_password']);
        }

        // Activation
        if (isset($_POST['activateSubmit'])) {
            if ($this->activate($_POST['email'], $_POST['code'])) {
                Redirect::redirectPage("login/login.php");
            }
        }

        // Password reset
        if (isset($_POST['requestPasswordReset'])) {
            PasswordReset::requestPasswordReset($_POST['user_name_or_email']);
        }
        if (isset($_POST['resetSubmit'])) {
            PasswordReset::verifyPasswordReset($_POST['password'], $_POST['resetCode']);
        }

        // My account
        if (isset($_POST['changeUsername'])) {
            if (User::editUserName($_POST['user_name'])) {
                Redirect::redirectPage("my-account/index.php");
            };
        }
        if (isset($_POST['changepassword'])) {
            if (Password::changePassword(Session::get('user_name'), $_POST['currentpassword'], $_POST['newpassword'], $_POST['newconfirmpassword'])) {
                Redirect::redirectPage("my-account/index.php");
            };
        }
        if (isset($_POST['clearUserFbid'])) {
            User::clearFbid();
        }

        // Events
        if (isset($_POST['addEvent'])) {
            Event::addEvent();
        }
        if (isset($_POST['updateEvent'])) {
            Event::updateEvent();
        }
        if (isset($_POST['deleteEvent'])) {
            if (Event::deleteEvent($_POST['id'])) {
                Redirect::redirectPage("events/index.php");
            }
        }

        // Members
        if (isset($_POST['saveMember'])) {
            Member::saveMember();
        }
        if (isset($_POST['saveNewMember'])) {
            Member::newMember();
        }

        // Contracts
        if (isset($_POST['updateContract'])) {
            Contract::update();
        }
        if (isset($_POST['newContract'])) {
            Contract::new();
        }

        // Products
        if (isset($_POST['saveNewProduct'])) {
            Product::new();
        }

        // Invoices
        if (isset($_POST['saveNewInvoice'])) {
            Invoice::new();
        }
        if (isset($_POST['deleteinvoiceitem'])) {
            Invoice::deleteInvoiceItem();
        }
        if (isset($_POST['addinvoiceitem'])) {
            Invoice::addInvoiceItem();
        }

        // Page
        if (isset($_POST['updatePage'])) {
            if (Page::updatePage($_POST['id'], $_POST['content'])) {
                Redirect::redirectPage("home/index.php");
            };
        }

        // Mail schedule
        if (isset($_POST['testmail'])) {
            MailController::sendMail($_POST['senderemail'], $_POST['recipientemail'], $_POST['subject'], $_POST['body']);
        }
        if (isset($_POST['testeventmail'])) {
            if (MailController::sendEventMail($_POST['testeventmail_recipientemail'])) {
                Redirect::redirectPage("settings/debug/");
            }
        }
        if (isset($_POST['newScheduledMail'])) {
            MailSchedule::new();
        }
        if (isset($_POST['sendScheduledMailById'])) {
            MailSchedule::sendbyid();
        }
        if (isset($_POST['createMailWithTemplate'])) {
            MailSchedule::newWithTemplate();
        }

        // Site settings
        if (isset($_POST['saveSiteSettings'])) {
            if (SiteSettings::saveSiteSettings()) {
                $_SESSION['response'][] = array("status"=>"success", "message"=>"Instellingen succesvol opgeslagen.");
                Redirect::redirectPage("settings/site-settings/index.php");
            } else {
                $_SESSION['response'][] = array("status"=>"warning", "message"=>"Fout bij opslaan van instellingen.");
                Redirect::redirectPage("settings/site-settings/index.php");
            }
        }
        // Roles
        if (isset($_POST['assignrole'])) {
            Role::assign($_POST['user_id'], $_POST['role_id']);
        }
        if (isset($_POST['unassignrole'])) {
            Role::unassign($_POST['user_id'], $_POST['role_id']);
        }
        if (isset($_POST['deleterole'])) {
            Role::delete($_POST['role_id']);
        }
        if (isset($_POST['addrole'])) {
            Role::create($_POST['role_name']);
        }
        if (isset($_POST['setrolepermission'])) {
            Permission::assign($_POST['role_id'], $_POST['perm_id']);
        }
        if (isset($_POST['deleterolepermission'])) {
            Permission::unassign($_POST['role_id'], $_POST['perm_id']);
        }
        // if (isset($_POST['deleteuser'])) {
        //     $this->deleteUser($_POST['user_id']);
        // }




    }
}