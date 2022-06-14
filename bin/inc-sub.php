<?php
/**
 * This file include all files for sub path caller, so that COSP work properly
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * includes all needed files; used in files in this directory level
 */

header('Content-Type: text/html; charset=utf-8');
require_once '../bin/config.php';
require_once '../bin/MailTemplates.php';
require_once '../bin/settings.php';
require_once '../bin/database/inc-db-sub.php';
require_once '../bin/deletions.php';
require_once '../bin/functionLib.php';
require_once '../bin/authSystem.php';
require_once '../bin/SessionValues.php';
require_once '../bin/api-functions.php';
require_once '../bin/uapi-functions.php';
require_once '../bin/mapi-functions.php';
require_once '../bin/mailer.php';
require_once '../bin/statistic-calc.php';
require_once '../bin/captcha.php';
require_once '../bin/browser-recognition.php';
require_once '../bin/csrf.php';

/**
 * sets parameters to debug if debug mode is enabled
 */
if (config::$DEBUG === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}