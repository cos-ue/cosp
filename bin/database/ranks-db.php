<?php
/**
 * This File includes all needed functions for ranks-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * get list of all ranks
 * @return array structured result data
 */
function getRanks()
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'ranks` ;';
    $params = array();
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}

/**
 * Adds new rank to database
 * @param int $value value of new rank
 * @param string $name name of new rank
 * @return bool|null true if successfull
 */
function addRank($value, $name)
{
    $prep_stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'ranks` ( `value` , `name` ) VALUES ( ? , ? );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $value;
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $name;
    $result = ExecuteStatementWOR($prep_stmt, $params);
    return $result;
}

/**
 * deletes a role
 * @param int $rid unique role identifier of role which should be removed
 */
function deleteRank($rid)
{
    $prep_stmt = "DELETE FROM `" . config::$SQL_PREFIX . "ranks` WHERE `id` = :id";
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $rid;
    $params[0]['nam'] = ":id";
    dump($params, 8);
    ExecuteStatementWR($prep_stmt, $params, false);
    return;
}

/**
 * updates rolename and/or value of selected role
 * @param int $id unique role identifier of role which should be changed
 * @param int $value changed value of role
 * @param string $name changed name of role
 */
function updateRank($id, $value, $name)
{
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'ranks` SET `value` = :value , name = :name WHERE `id` = :id ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $value;
    $params[0]['nam'] = ":value";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $name;
    $params[1]['nam'] = ":name";
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $id;
    $params[2]['nam'] = ":id";
    ExecuteStatementWR($prep_stmt, $params, false);
}