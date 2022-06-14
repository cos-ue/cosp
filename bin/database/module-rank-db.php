<?php
/**
 * This File includes all needed functions for module based rank-names database
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * get All Data for module based name of a certain rank
 * @param int $rid Identifier of rank
 * @return array structured result
 */
function getNamesByRankId($rid)
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'module-rank` where rid = :rid ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $rid;
    $params[0]['nam'] = ":rid";
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}

/**
 * adds a new module based rank name
 * @param int $rid identifier of rank
 * @param int $aid identifier of module
 * @param string $name module based rankname
 * @return array|bool|void structured result
 */
function insertModuleBasedRankName($rid, $aid, $name)
{
    $stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'module-rank` ( `aid` , `rid` , `name` ) values ( :aid , :rid , :name );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $aid;
    $params[0]['nam'] = ":aid";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $rid;
    $params[1]['nam'] = ":rid";
    $params[2] = array();
    $params[2]['typ'] = 'd';
    $params[2]['val'] = $name;
    $params[2]['nam'] = ":name";
    $result = ExecuteStatementWR($stmt, $params, false);
    return $result;
}

/**
 * deletes a certain module based rank name
 * @param int $id identifier of Module based rank name
 * @return array|void|null structured result
 */
function deleteModuleBasedRankName($id)
{
    $prep_stmt = "DELETE FROM `" . config::$SQL_PREFIX . "module-rank` WHERE id = :id";
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $id;
    $params[0]['nam'] = ":id";
    dump($params, 8);
    $x = ExecuteStatementWR($prep_stmt, $params, false);
    return $x;
}

/**
 * selects all ranks depending on module
 * @param int $aid identifier of module
 * @return array|void|null structured result
 */
function getRankNamesByModule($aid)
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'module-rank` where aid = :aid ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $aid;
    $params[0]['nam'] = ":aid";
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}