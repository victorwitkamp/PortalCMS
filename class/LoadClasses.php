<?php

if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php')) {
    echo 'No "vendor" directory found. Run "composer update" to get started.';
    die;
} else {
    include_once $_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php';
}

/* CORE CLASSES */
require_once DIR_CLASS_CORE."Auth.php";
require_once DIR_CLASS_CORE."Config.php";
require_once DIR_CLASS_CORE."Controller.php";
require_once DIR_CLASS_CORE."Csrf.php";
require_once DIR_CLASS_CORE.'DB.php';
require_once DIR_CLASS_CORE."Encryption.php";
require_once DIR_CLASS_CORE."Environment.php";
require_once DIR_CLASS_CORE."Filter.php";
require_once DIR_CLASS_CORE."MailSender.php";
require_once DIR_CLASS_CORE."Redirect.php";
require_once DIR_CLASS_CORE."Request.php";
require_once DIR_CLASS_CORE."Session.php";
require_once DIR_CLASS_CORE."Text.php";
require_once DIR_CLASS_CORE."View.php";

/* MODELS */
require_once DIR_CLASS_MODEL."Contract.php";
require_once DIR_CLASS_MODEL."Event.php";
require_once DIR_CLASS_MODEL."Invoice.php";
require_once DIR_CLASS_MODEL."Login.php";
require_once DIR_CLASS_MODEL."Member.php";
require_once DIR_CLASS_MODEL."MailSchedule.php";
require_once DIR_CLASS_MODEL."MailTemplate.php";
require_once DIR_CLASS_MODEL."Page.php";
require_once DIR_CLASS_MODEL."Password.php";
require_once DIR_CLASS_MODEL."PasswordReset.php";
require_once DIR_CLASS_MODEL."Permission.php";
require_once DIR_CLASS_MODEL."Product.php";
require_once DIR_CLASS_MODEL."RegistrationModel.php";
require_once DIR_CLASS_MODEL."Role.php";
require_once DIR_CLASS_MODEL.'SiteSettings.php';
require_once DIR_CLASS_MODEL."UserActivity.php";
require_once DIR_CLASS_MODEL."User.php";
require_once DIR_CLASS_MODEL."Util.php";


/* CONTROLLERS */
require_once DIR_CLASS_CONTROLLER."LoginController.php";
require_once DIR_CLASS_CONTROLLER.'MailController.php';
require_once DIR_CLASS_CONTROLLER.'POSTController.php';

$login = new LoginController;
$MailController = new MailController;
$POSTController = new POSTController;

require_once $_SERVER["DOCUMENT_ROOT"].'/includes/tcpdf_config_alt.php';
// require_once $_SERVER["DOCUMENT_ROOT"].'/includes/TCPDF/tcpdf.php';