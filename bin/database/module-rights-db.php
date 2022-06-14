<?php
/**
 * This File includes all needed functions for module based rights database
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * gets all user-based rights
 * @param int $id identifier of user
 * @return array structured result
 */
function getAllModuleBasedRightsByUserID($id) {
    $prep_stmt = 'select RT.id, AT.name as api, AT.id as apiid, DR.name, DR.value, RT.disabled from `' . config::$SQL_PREFIX . 'rights-tools` as RT join `' . config::$SQL_PREFIX . 'api-token` as AT on RT.aid = AT.id join `' . config::$SQL_PREFIX . 'roles` as DR on RT.`role` = DR.id where uid = :uid ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $id;
    $params[0]['nam'] = ":uid";
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}

/**
 * inserts new module based right for user
 * @param string $name username of user which should be added
 * @param int $roleId identifier of role
 * @param int $appID identifier of api
 * @return bool|null structured result
 */
function addModuleBasedRole($nameId, $roleId, $appID)
{
    $prep_stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'rights-tools` ( `uid` , `aid`, `role`, `disabled` ) VALUES ( ? , ? , ? , ? );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $nameId;
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $appID;
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $roleId;
    $params[3] = array();
    $params[3]['typ'] = 's';
    $params[3]['val'] = getUserDataByID($nameId)['enabled'] == 0 ? 1 : 0;
    $result = ExecuteStatementWOR($prep_stmt, $params);
    return $result;
}

/**
 * gethers data for single user module right
 * @param int $id identifier of module right
 * @return array structured result
 */
function getModuleBasedRightsByID($id) {
    $prep_stmt = 'select RT.id, AT.name as api, AT.id as apiid, DR.name, DR.value as rolevalue, RT.disabled, uid, UL.name as username from `' . config::$SQL_PREFIX . 'rights-tools` as RT join `' . config::$SQL_PREFIX . 'api-token` as AT on RT.aid = AT.id join `' . config::$SQL_PREFIX . 'roles` as DR on RT.`role` = DR.id join `' . config::$SQL_PREFIX . 'user-login` as UL on RT.uid = UL.id where RT.id = :id ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $id;
    $params[0]['nam'] = ":id";
    $result = ExecuteStatementWR($prep_stmt, $params);
    if (count($result) > 0) {
        $result = $result[0];
    }
    return $result;
}

/**
 * deletes a module based right
 * @param int $id identifier of module based right
 */
function deleteModuleBasedRight($id) {
    $prep_stmt = "DELETE FROM `" . config::$SQL_PREFIX . "rights-tools` WHERE id = :id";
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $id;
    $params[0]['nam'] = ":id";
    dump($params, 8);
    ExecuteStatementWR($prep_stmt, $params, false);
}

/**
 * gets all user-based rights
 * @param int $id identifier of module
 * @return array structured result
 */
function getAllModuleBasedRightsByModulID($id) {
    $prep_stmt = 'select RT.id, AT.name as api, AT.id as apiid, DR.name as rolename, DR.value, DR.id as roleid, RT.disabled, uid, UL.name as username, UL.firstname, UL.lastname, UL.id as userid from `' . config::$SQL_PREFIX . 'rights-tools` as RT join `' . config::$SQL_PREFIX . 'api-token` as AT on RT.aid = AT.id join `' . config::$SQL_PREFIX . 'roles` as DR on RT.`role` = DR.id join `' . config::$SQL_PREFIX . 'user-login` as UL on RT.uid = UL.id where AT.id = :id ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $id;
    $params[0]['nam'] = ":id";
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}

/**
 * updates role of certain module right
 * @param int $rightID identifier of right
 * @param int $roleID identifier of role
 * @return boolean|null result
 */
function updateModulRights($rightID, $roleID) {
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'rights-tools` SET `role` = :role where `id` = :id ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $roleID;
    $params[0]['nam'] = ":role";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $rightID;
    $params[1]['nam'] = ":id";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params, false);
    dump($result, 9);
    return $result;
}

/**
 * selects identifier of user from database which have at least a certain permission
 * @param int $aid identifier of module
 * @param int $ReqPermissionValue required role value
 * @return array result as array
 */
function getPermissionUsersOfModule($aid, $ReqPermissionValue) {
    $prep_stmt = 'select uid from `' . config::$SQL_PREFIX . 'rights-tools` as RT join `' . config::$SQL_PREFIX . 'roles` as DR on RT.`role` = DR.id where DR.value >= :value and aid = :aid ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $aid;
    $params[0]['nam'] = ":aid";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $ReqPermissionValue;
    $params[1]['nam'] = ":value";
    $resultDB = ExecuteStatementWR($prep_stmt, $params);
    $result = array();
    if (count($resultDB) > 0) {
        foreach ($resultDB as $date) {
            $result[] = $date['uid'];
        }
    }
    return $result;
}

/**
 * updates disable state of certain module right
 * @param int $rightID identifier of module right
 * @param bool $state state of deactivation
 * @return bool|null state of request
 */
function updateDisableStateModuleRight($rightID, $state) {
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'rights-tools` SET `disabled` = :disabled where `id` = :id ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $state ? 0 : 1;
    $params[0]['nam'] = ":disabled";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $rightID;
    $params[1]['nam'] = ":id";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params, false);
    dump($result, 9);
    return $result;
}

/**
 * selects module based right with username and api token
 * @param string $username identifier of user
 * @param string $token alphanumeric identifier of module
 * @return array structured result;
 */
function getModuleRightByUsernameApiToken($username, $token) {
    $prep_stmt = 'select RT.id, AT.name as api, AT.id as apiid, AT.token, DR.name as rolename, DR.value as rolevalue, DR.id as roleid, RT.disabled, uid, UL.name as username, UL.firstname, UL.lastname from `' . config::$SQL_PREFIX . 'rights-tools` as RT join `' . config::$SQL_PREFIX . 'api-token` as AT on RT.aid = AT.id join `' . config::$SQL_PREFIX . 'roles` as DR on RT.`role` = DR.id join `' . config::$SQL_PREFIX . 'user-login` as UL on RT.uid = UL.id where AT.token = :token and UL.name = :name ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":token";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $username;
    $params[1]['nam'] = ":name";
    $result = ExecuteStatementWR($prep_stmt, $params);
    if (count($result) > 0) {
        $result = $result[0];
        unset($result[0]);
        unset($result[1]);
        unset($result[2]);
        unset($result[3]);
        unset($result[4]);
        unset($result[5]);
        unset($result[6]);
        unset($result[7]);
        unset($result[8]);
        unset($result[9]);
        unset($result[10]);
        unset($result[11]);
    } else {
        return array();
    }
    return $result;
}