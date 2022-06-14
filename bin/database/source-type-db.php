<?php
/**
 * This File includes all needed functions for source type table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * selects all relations for types for sources
 * @return array structured result
 */
function getAllSourceTypes() {
    $prep_stmt = "SELECT *  FROM `" . config::$SQL_PREFIX . "source_type`;";
    $params = array();
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}