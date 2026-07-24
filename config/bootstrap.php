<?php

declare(strict_types=1);

require __DIR__ . '/error-reporting.php';
require __DIR__ . '/constants.php';

if (!is_dir(DIR_TEMP)) {
    throw new RuntimeException(sprintf('Directory "%s" was not created', DIR_TEMP));
}
if (!is_file(DIR_VENDOR . 'autoload.php')) {
    throw new RuntimeException('No Composer autoloader found. Run composer install.');
}

require_once DIR_VENDOR . 'autoload.php';
