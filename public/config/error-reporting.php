<?php
/**
 * Configuration for: Error reporting
 */
error_reporting(E_ALL);
ini_set('ignore_repeated_errors', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/errors.log');
ini_set('log_errors_max_len', 1024);
