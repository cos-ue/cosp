<?php
/**
 * This File includes all needed functions for pictures-validate-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * adds new validation value to database for a given picture
 * @param int $picture_id id of picture
 * @param int $value value which is added to validation value of picture
 * @param string $username username of approving user
 * @return bool|null state of request
 */
function insertValidatePictures($picture_id, $value, $username)
{
    $stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'pictures-validate` ( `picture-id` , `user-id` , `value` ) values ( :pid , :uid , :val );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 'i';
    $params[0]['val'] = $picture_id;
    $params[0]['nam'] = ":pid";
    $params[1] = array();
    $params[1]['typ'] = 'i';
    $params[1]['val'] = getUserData($username)['id'];
    $params[1]['nam'] = ":uid";
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $value;
    $params[2]['nam'] = ":val";
    $result = ExecuteStatementWR($stmt, $params, false);
    return $result;
}

/**
 * deletes all Validations for a picture
 * @param int $pid picture id
 * @return bool|null state of request
 */
function deleteValidatePictures($pid)
{
    $prep_stmt = "DELETE FROM `" . config::$SQL_PREFIX . "pictures-validate` WHERE picture-id = :pic_id";
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $pid;
    $params[0]['nam'] = ":pic_id";
    dump($params, 8);
    $x = ExecuteStatementWR($prep_stmt, $params, false);
    return $x;
}

/**
 * gets all users which validated a given picture
 * @param int $pid id of picture
 * @return array structured result
 */
function getUservalidationsPictures($pid)
{
    $stmt = 'SELECT name FROM  `' . config::$SQL_PREFIX . 'pictures-validate` join `' . config::$SQL_PREFIX . 'user-login` on `user-id` = `dload__user-login`.`id` where `picture-id` = :pid  ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 'i';
    $params[0]['val'] = $pid;
    $params[0]['nam'] = ":pid";
    $result = ExecuteStatementWR($stmt, $params);
    $result2 = array();
    foreach ($result as $r)
    {
        $result2[] = $r['name'];
    }
    return $result2;
}

/**
 * get suim of validation values for a given picture
 * @param int $pid id of picture
 * @return int sum of validation values
 */
function getValidateSumPictures($pid)
{
    $stmt = 'SELECT SUM(`value`) FROM  `' . config::$SQL_PREFIX . 'pictures-validate` where `picture-id` = :pid ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 'i';
    $params[0]['val'] = $pid;
    $params[0]['nam'] = ":pid";
    $result = ExecuteStatementWR($stmt, $params);
    return $result[0]['SUM(`value`)'] == null ? 0 : $result[0]['SUM(`value`)'];
}