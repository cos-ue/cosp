<?php
/**
 * This File includes all needed functions for point-origin-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * gets list of reason for rank points
 * @return array structured result
 */
function getAllReasons()
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'point-origin` ;';
    $params = array();
    $result = ExecuteStatementWR($prep_stmt, $params);
    $result2 = array();
    foreach ($result as $entry) {
        $result2[$entry['id']] = $entry['name'];
    }
    return $result2;
}

/**
 * adds new reason for rank points
 * @param string $name name of new reason
 * @return bool|null state of request
 */
function addReason($name)
{
    $prep_stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'point-origin` ( `name` ) VALUES ( ? );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $name;
    $result = ExecuteStatementWOR($prep_stmt, $params);
    return $result;
}