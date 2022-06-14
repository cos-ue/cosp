<?php
/**
 * This File includes all needed functions for stories-validate-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * insert validation value for story
 * @param int $story_id id of story
 * @param int $value value which is added to validation value of story
 * @param string $username username whose approved story
 * @return bool|null state of request
 */
function insertValidateStories($story_id, $value, $username)
{
    $stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'stories-validate` ( sid , uid , `value` ) values ( :sid , :uid , :value );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 'i';
    $params[0]['val'] = $story_id;
    $params[0]['nam'] = ":sid";
    $params[1] = array();
    $params[1]['typ'] = 'i';
    $params[1]['val'] = getUserData($username)['id'];
    $params[1]['nam'] = ":uid";
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $value;
    $params[2]['nam'] = ":value";
    $result = ExecuteStatementWR($stmt, $params, false);
    return $result;
}

/**
 * gets usernames of users which approved a certain story
 * @param int $sid id of story
 * @return array structured result
 */
function getUservalidationsStory($sid)
{
    $stmt = 'SELECT name FROM  `' . config::$SQL_PREFIX . 'stories-validate` join `' . config::$SQL_PREFIX . 'user-login` on `uid` = `dload__user-login`.`id` where `sid` = :sid  ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 'i';
    $params[0]['val'] = $sid;
    $params[0]['nam'] = ":sid";
    $result = ExecuteStatementWR($stmt, $params);
    $result2 = array();
    foreach ($result as $r)
    {
        $result2[] = $r['name'];
    }
    return $result2;
}

/**
 * get validation value of story
 * @param int $sid id of story
 * @return int result is sum  of all validations
 */
function getValidateSumStories($sid)
{
    $stmt = 'SELECT SUM(value) FROM  `' . config::$SQL_PREFIX . 'stories-validate` where sid = :sid ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 'i';
    $params[0]['val'] = $sid;
    $params[0]['nam'] = ":sid";
    $result = ExecuteStatementWR($stmt, $params);
    return $result[0]['SUM(value)'] == null ? 0 : $result[0]['SUM(value)'];
}

/**
 * deletes Validates from certain story
 * @param int $sid story id
 * @return bool|null state of request
 */
function deleteStoryValidates($sid) {
    $stmt = 'Delete FROM  `' . config::$SQL_PREFIX . 'stories-validate` where sid = :sid ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 'i';
    $params[0]['val'] = $sid;
    $params[0]['nam'] = ":sid";
    $x = ExecuteStatementWR($stmt, $params, false);
    return $x;
}