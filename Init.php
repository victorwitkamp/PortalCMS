<?php
/**
 * Initialization file
 *
 * @package PortalCMS
 * @link    https://portal.victorwitkamp.nl/
 */

date_default_timezone_set('Europe/Amsterdam');

/**
 * Configuration for: Error reporting
 */
error_reporting(E_ALL); // Error engine - always true!
// Passing in the value -1 will show every possible error, even when new levels and constants are added in future PHP versions.
// The E_ALL constant also behaves this way as of PHP 5.4.
//error_reporting(-1);
ini_set('ignore_repeated_errors', 0); // always true
ini_set('display_errors', 1); // Error display - false only in production environment or real server
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER["DOCUMENT_ROOT"] . '/errors.log');
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

/**
 * Configuration for: Named constants
 */
define("DIR_ROOT", $_SERVER["DOCUMENT_ROOT"] . "/");
define("DIR_CLASS", DIR_ROOT . "class/");
define("DIR_INCLUDES", DIR_ROOT . "includes/");
define("DIR_VENDOR", DIR_ROOT . "vendor/");

if (!file_exists(DIR_VENDOR . 'autoload.php')) {
    echo 'No autoloader found in the "vendor" directory. Run "composer update" to get started.';
    die;
} else {
    include_once DIR_VENDOR . 'autoload.php';
}

spl_autoload_register(
    function ($class) {
        $sources = array(
            DIR_CLASS."Core/$class.php",
            DIR_CLASS."Model/$class.php",
            DIR_CLASS."Controller/$class.php",
            DIR_CLASS."DataMapper/$class.php"
        );
        foreach ($sources as $source) {
            if (file_exists($source)) {
                require_once $source;
            }
        }
    }
);

$AccountController = new AccountController;
$ContractController = new ContractController;
$EventController = new EventController;
$InvoiceController = new InvoiceController;
$login = new LoginController;
$MailController = new MailController;
$MembershipController = new MembershipController;
$PageController = new PageController;
$PasswordResetController = new PasswordResetController;
$ProductController = new ProductController;
$RoleController = new RoleController;
$SiteSettingController = new SiteSettingController;
$UserController = new UserController;

require_once $_SERVER["DOCUMENT_ROOT"].'/includes/tcpdf_config_alt.php';
