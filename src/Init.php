<?php
/**
 * Initialization file
 *
 * @package PortalCMS
 * @link    https://portal.victorwitkamp.nl/
 */

require __DIR__ . '/../config/error-reporting.php';
require __DIR__ . '/../config/session.php';
require __DIR__ . '/../config/constants.php';

if (!file_exists(DIR_TEMP) && !mkdir(DIR_TEMP, 0777, true) && !is_dir(DIR_TEMP)) {
    throw new RuntimeException(sprintf('Directory "%s" was not created', DIR_TEMP));
}

if (!file_exists(DIR_VENDOR . 'autoload.php')) {
    echo 'No autoloader found in the "vendor" directory. Run "composer update" to get started.';
    die;
}

include_once DIR_VENDOR . 'autoload.php';
