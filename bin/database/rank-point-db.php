<?php
/**
 * This File includes all needed functions for ranksystem-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * adds points to users rank account
 * @param int $uid id of user
 * @param int $aid id of application
 * @param int $value value to add to users account
 * @param int $oid id of point reason name
 * @return bool|null state of request
 */
function addPoints($uid, $aid, $value, $oid)
{
    $prep_stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'ranksystem` ( `uid` , `aid` , `value` , `oid` ) VALUES ( ? , ? , ? , ? );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $uid;
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $aid;
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $value;
    $params[3] = array();
    $params[3]['typ'] = 's';
    $params[3]['val'] = $oid;
    $result = ExecuteStatementWOR($prep_stmt, $params);
    return $result;
}

/**
 * get points of user
 * @param int $uid id of user
 * @param int $aid id of application
 * @return array structured result
 */
function getPointsOfUser($uid, $aid)
{
    $prep_stmt = 'select SUM(`value`) from `' . config::$SQL_PREFIX . 'ranksystem` where `uid` = :uid and `aid` = :aid ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $uid;
    $params[0]['nam'] = ":uid";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $aid;
    $params[1]['nam'] = ":aid";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    if (count($result) > 0) {
        $result = $result[0];
    }
    dump($result, 7);
    return $result;
}

/**
 * get points of user only for last year (today - 1 year)
 * @param int $uid id of user
 * @param int $aid id of application
 * @return array structured result
 * @throws Exception throws Exception in case of an Exception
 */
function getPointsOfUserLastYear($uid, $aid)
{
    $time = new DateTime();
    $time->modify('-1 year');
    $prep_stmt = 'select SUM(`value`) from `' . config::$SQL_PREFIX . 'ranksystem` where `uid` = :uid and `aid` = :aid and `date` >= :dat ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $uid;
    $params[0]['nam'] = ":uid";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $aid;
    $params[1]['nam'] = ":aid";
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $time->format('Y-m-d H:i:s');
    $params[2]['nam'] = ":dat";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    if (count($result) > 0) {
        $result = $result[0];
    }
    dump($result, 7);
    return $result;
}

/**
 * gets rank table for certain application
 * @param int $aid id of application
 * @return array structured result data
 * @throws Exception throws Exception in case of an Exception
 */
function getRanktableForApplication($aid)
{
    $time = new DateTime();
    $time->modify('-1 year');
    $prep_stmt = 'SELECT SUM(`value`) SUMTOTAL, SUM(CASE WHEN date >= :dat Then `value` else 0 END) SUMYEAR , `name` FROM `' . config::$SQL_PREFIX . 'user-login` join ' . config::$SQL_PREFIX . 'ranksystem on `' . config::$SQL_PREFIX . 'user-login`.id = ' . config::$SQL_PREFIX . 'ranksystem.uid where aid = :aid group by `name` order by SUMYEAR desc;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $aid;
    $params[0]['nam'] = ":aid";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $time->format('Y-m-d H:i:s');
    $params[1]['nam'] = ":dat";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}

/**
 * gets all Data from ranksystem
 * @return array structured query result
 */
function getRankDataForAllUsers(){
    $prep_stmt = 'select R.date, U.name as user , A.name as modul , O.name as reason , R.value from `' . config::$SQL_PREFIX . 'ranksystem` as R join `' . config::$SQL_PREFIX . 'user-login` as `U` on R.uid = U.id join `' . config::$SQL_PREFIX . 'point-origin` as `O` on R.oid = `O`.id join `' . config::$SQL_PREFIX . 'api-token` as `A` on R.aid = `A`.id;';
    $params = array();
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}