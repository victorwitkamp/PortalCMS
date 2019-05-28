<?php

/**
 * Config for Portal
 *
 * @package PortalCMS
 * @link    https://portal.victorwitkamp.nl/
 */

date_default_timezone_set('Europe/Amsterdam');

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard / no errors in production.
 * It's a little bit dirty to put this here, but who cares. For development purposes it's totally okay.
 */

//error_reporting(E_ALL); // Error engine - always TRUE!

// Passing in the value -1 will show every possible error, even when new levels and constants are added in future PHP versions.
// The E_ALL constant also behaves this way as of PHP 5.4.
error_reporting(-1);

ini_set('ignore_repeated_errors', false); // always TRUE

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// ini_set('display_errors', TRUE); // Error display - FALSE only in production environment or real server
ini_set('log_errors', TRUE); // Error logging engine
ini_set('error_log', $_SERVER["DOCUMENT_ROOT"] . '/errors.log'); // Logging file path
ini_set('log_errors_max_len', 1024); // Logging file size

/**
 * Configuration for cookie security
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
define("DIR_CLASS", $_SERVER["DOCUMENT_ROOT"] . "/class/");
define("DIR_CLASS_CORE", $_SERVER["DOCUMENT_ROOT"] . "/class/core/");
define("DIR_CLASS_MODEL", $_SERVER["DOCUMENT_ROOT"] . "/class/model/");
define("DIR_CLASS_CONTROLLER", $_SERVER["DOCUMENT_ROOT"] . "/class/controller/");
define("DIR_INCLUDES", $_SERVER["DOCUMENT_ROOT"] . "/includes/");

define('FB_LOGIN_URL', 'https://portal.victorwitkamp.nl/login/ext/fb/fb-callback-login.php');
define('FB_ASSIGN_URL', 'https://portal.victorwitkamp.nl/login/ext/fb/fb-callback.php');