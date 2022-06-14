<?php
/**
 * This File includes all needed functions for roles-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * returns List of all existing Roles
 * @return array structured result data
 */
function getAllRolles()
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'roles` ;';
    $params = array();
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}

/**
 * Selects role based on unique role identifier
 * @param int $id unique role identifier of role which should be selected
 * @return array|bool|null structured result data if $id was existent
 */
function getRoleByID($id)
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'roles` WHERE `id` = :id;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $id;
    $params[0]['nam'] = ":id";
    $result = ExecuteStatementWR($prep_stmt, $params);
    if (count($result) == 0) {
        return array();
    }
    return $result[0];
}

/**
 * deletes a role
 * @param int $rid unique role identifier of role which should be removed
 */
function deleteRole($rid)
{
    $prep_stmt = "DELETE FROM `" . config::$SQL_PREFIX . "roles` WHERE `id` = :id";
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
 * Adds new role to database
 * @param int $value value of new role
 * @param string $name name of new role
 * @return bool|null true if successfull
 */
function addRole($value, $name)
{
    $prep_stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'roles` ( `value` , `name` ) VALUES ( ? , ? );';
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
 * updates rolename and/or value of selected role
 * @param int $id unique role identifier of role which should be changed
 * @param int $value changed value of role
 * @param string $name changed name of role
 */
function updateRole($id, $value, $name)
{
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'roles` SET `value` = :value , name = :name WHERE `id` = :id ;';
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