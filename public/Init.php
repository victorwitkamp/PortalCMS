<?php
/**
 * Initialization file
 *
 * @package PortalCMS
 * @link    https://portal.victorwitkamp.nl/
 */

/**
 * Configuration for: Error reporting
 */
error_reporting(E_ALL);
ini_set('ignore_repeated_errors', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER["DOCUMENT_ROOT"].'/errors.log');
ini_set('log_errors_max_len', 1024);

/**
 * Configuration for: cookie security
 *
 * Quote from PHP manual: Marks the cookie as accessible only through the HTTP protocol. This means that the cookie
 * won't be accessible by scripting languages, such as JavaScript. This setting can effectively help to reduce identity
 * theft through XSS attacks (although it is not supported by all browsers).
 *
 * @see php.net/manual/en/session.configuration.php#ini.session.cookie-httponly
 */
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

/**
 * Configuration for: Named constants
 */
define("DIR_ROOT", $_SERVER["DOCUMENT_ROOT"]."/");
define("DIR_INCLUDES", DIR_ROOT."includes/");
define("DIR_VENDOR", DIR_ROOT."../vendor/");
define("DIR_TEMP", DIR_ROOT."content/temp/");
if (!file_exists(DIR_TEMP)) {
    mkdir(DIR_TEMP, 0777, true);
}

if (!file_exists(DIR_VENDOR.'autoload.php')) {
    echo 'No autoloader found in the "vendor" directory. Run "composer update" to get started.';
    die;
} else {
    include_once DIR_VENDOR.'autoload.php';
}

$AccountController = new AccountController();
$ContractController = new ContractController();
$EventController = new EventController();
$InvoiceController = new InvoiceController();
$login = new LoginController();
$MailController = new MailController();
$MailTemplateController = new MailTemplateController();
$MembershipController = new MembershipController();
$PageController = new PageController();
$PasswordResetController = new PasswordResetController();
$RoleController = new RoleController();
$SiteSettingController = new SiteSettingController();
$UserController = new UserController();
