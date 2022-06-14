<?php
/**
 * This file contains all needed functions to write session data to database
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * called by php, opens a new session
 * @param string|int|array $path some path
 * @param string $name name of session
 * @return bool always true
 */
function ses_open($path, $name)
{
    return TRUE;
}

/**
 * opened by php, used to close session
 * @return bool always true
 */
function ses_close()
{
    return TRUE;
}

/**
 * reads session from database via database/session-db.php
 * @param string $ses_id identifier of session
 * @return string saved session data
 */
function ses_read($ses_id)
{
    $result = readSessionData($ses_id);
    return $result;
}

/**
 * writes session data to database
 * @param string $ses_id identifier of session
 * @param string $data session data as string
 * @return bool always true
 */
function ses_write($ses_id, $data)
{
    replaceSessionData($ses_id, $data);
    return true;
}

/**
 * deletes session from database
 * @param string $ses_id identifier of session
 * @return bool always true
 */
function ses_destroy($ses_id)
{
    deleteSessionData($ses_id);
    return true;
}

/**
 * cleans database from outdated entries of sessions
 * @param int $life time after which sessions will be deleted
 * @return bool
 */
function ses_gc($life)
{
    deleteSessionDataGC($life);
    return true;
}

/**
 * denies access to page if user is not logged in
 * @param bool $login status of user login
 * @return bool true if logged in otherwise permission will be denied
 */
function checkLoginDeny($login)
{
    if ($login == false) {
        permissionDenied();
    }
    return true;
}

/**
 * include session handlers into php-framework and set $LOGIN value
 */
session_set_save_handler('ses_open', 'ses_close', 'ses_read', 'ses_write', 'ses_destroy', 'ses_gc');
register_shutdown_function('session_write_close');
session_start();

/**
 * @const bool $LOGIN if true user is logged in
 */
$LOGIN = false;
if (isset($_SESSION)) {
    if (isset($_SESSION['name'])) {
        $LOGIN = true;
    }
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = generateRandomString(1024, false);
    }
}