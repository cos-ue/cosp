<?php
/**
 * This File includes all needed functions for session-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * replaces sessiondata in database
 * @param string $sessionID identifier of session
 * @param string $sessionData session data as string
 * @return bool always true
 */
function replaceSessionData($sessionID, $sessionData)
{
    $prep_stmt = 'REPLACE INTO ' . config::$SQL_PREFIX . 'session (ses_id, ses_time, ses_value) VALUES ( ? , ? , ? ); ';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $sessionID;
    $params[1] = array();
    $params[1]['typ'] = 'i';
    $params[1]['val'] = time();
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $sessionData;
    ExecuteStatementWOR($prep_stmt, $params);
    return true;
}

/**
 * gets sessiondata from database
 * @param string $SessionID identifier of session
 * @return string session data as string
 */
function readSessionData($SessionID)
{
    $prep_stmt = "SELECT ses_value FROM " . config::$SQL_PREFIX . "session WHERE ses_id = :sessionID";
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $SessionID;
    $params[0]['nam'] = ":sessionID";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    if (count($result) > 0) {
        $result = $result[0];
    }
    if (count($result) > 0) {
        $result = $result[0];
        return $result;
    }
    return '';
}

/**
 * deletes session data from database
 * @param string $SessionID identifier of session
 */
function deleteSessionData($SessionID)
{
    $prep_stmt = "DELETE FROM " . config::$SQL_PREFIX . "session WHERE ses_id = :sessionID";
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $SessionID;
    $params[0]['nam'] = ":sessionID";
    dump($params, 8);
    ExecuteStatementWR($prep_stmt, $params, false);
}

/**
 * deletes expired sessions
 * @param int $life lifetime of session in seconds
 */
function deleteSessionDataGC($life)
{
    $ses_life = time() - $life;
    $prep_stmt = "DELETE FROM " . config::$SQL_PREFIX . "session WHERE ses_time < :timer";
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $ses_life;
    $params[0]['nam'] = ":timer";
    dump($params, 8);
    ExecuteStatementWR($prep_stmt, $params, false);
    return;
}