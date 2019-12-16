<?php
/**
 * Initialization file
 *
 * @package PortalCMS
 * @link    https://portal.victorwitkamp.nl/
 */

require $_SERVER['DOCUMENT_ROOT'] . '/../config/error-reporting.php';
require $_SERVER['DOCUMENT_ROOT'] . '/../config/session.php';
require $_SERVER['DOCUMENT_ROOT'] . '/../config/constants.php';

if (!file_exists(DIR_TEMP)) {
    if (!mkdir($concurrentDirectory = DIR_TEMP, 0777, true) && !is_dir($concurrentDirectory)) {
        throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
    }
}

if (!file_exists(DIR_VENDOR . 'autoload.php')) {
    echo 'No autoloader found in the "vendor" directory. Run "composer update" to get started.';
    die;
} else {
    include_once DIR_VENDOR . 'autoload.php';
}

$MailController = new PortalCMS\Controllers\MailController();
$MailTemplateController = new PortalCMS\Controllers\MailTemplateController();
// $PasswordResetController = new PortalCMS\Controllers\PasswordResetController();
$RoleController = new PortalCMS\Controllers\RoleController();
$UserController = new PortalCMS\Controllers\UserController();
