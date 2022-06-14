<?php
/**
 * This File includes all needed functions for visitors-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * Inserts database entry for unique visitor
 * @param string $ip ip of visitor
 * @param string $type type of visitor
 * @return bool|null state of request
 */
function insertLogUniqueVisitors($ip, $type)
{
    $stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'visitors` (`ip`, `type`) values ( :ip , :type );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 'i';
    $params[0]['val'] = $ip;
    $params[0]['nam'] = ":ip";
    $params[1] = array();
    $params[1]['typ'] = 'i';
    $params[1]['val'] = $type;
    $params[1]['nam'] = ":type";
    $result = ExecuteStatementWR($stmt, $params, false);
    return $result;
}

/**
 * get's statistical data for displaying as graph over the given amount of past weeks starting today
 * @param int $number number of weeks
 * @return array structured result
 */
function getStatisticalDataLastWeeks($number)
{
    $stmt = "select Count(DISTINCT (`ip`)) as counter, DATE_FORMAT(`date`,'%Y-%m-%d') as time , `type` from `" . config::$SQL_PREFIX . "visitors` where date >= DATE(NOW()) - INTERVAL :number WEEK group by `time`, `type` ;";
    return ExecuteStatisticStatement($stmt, $number);
}

/**
 * get's statistical data for displaying as graph over the given amount of past month starting today
 * @param int $number number of month
 * @return array structured result
 */
function getStatisticalDataLastMonth($number)
{
    $stmt = "select Count(DISTINCT (`ip`)) as counter, DATE_FORMAT(`date`,'%Y-%m-%d') as time , `type` from `" . config::$SQL_PREFIX . "visitors` where date >= DATE(NOW()) - INTERVAL :number MONTH group by `time`, `type` ;";
    return ExecuteStatisticStatement($stmt, $number);
}

/**
 * get's statistical data for displaying as graph over the given amount of past year starting today
 * @param int $number number of years
 * @return array structured result
 */
function getStatisticalDataLastYear($number)
{
    $stmt = "select Count(DISTINCT (`ip`)) as counter, DATE_FORMAT(`date`,'%Y-%m-%d') as time , `type` from `" . config::$SQL_PREFIX . "visitors` where date >= DATE(NOW()) - INTERVAL :number YEAR group by `time`, `type` ;";
    return ExecuteStatisticStatement($stmt, $number);
}

/**
 * get's statistical data for displaying as graph over the given amount of past days starting today
 * @param int $number number of days
 * @return array structured result
 */
function getStatisticalDataLastDays($number)
{
    $stmt = "select Count(DISTINCT (`ip`)) as counter, DATE_FORMAT(`date`,'%Y-%m-%d') as time , `type` from `" . config::$SQL_PREFIX . "visitors` where date >= DATE(NOW()) - INTERVAL :number DAY group by `time`, `type` ;";
    return ExecuteStatisticStatement($stmt, $number);
}