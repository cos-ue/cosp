<?php
/**
 * This File includes all needed functions for statistics contact form table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * Inserts database entry for send contact message
 * @param string $ip ip of visitor
 * @param int $aid id of module from which contact-form request is incomming standard is null
 * @return bool|null state of request
 */
function insertLogContactMail($ip, $aid = "")
{
    $stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'user_requests` (`ip` , `module`) values ( :ip , :module );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 'i';
    $params[0]['val'] = $ip;
    $params[0]['nam'] = ":ip";
    $params[1] = array();
    $params[1]['typ'] = 'i';
    $params[1]['val'] = $aid;
    $params[1]['nam'] = ":module";
    $result = ExecuteStatementWR($stmt, $params, false, false);
    return $result;
}

/**
 * get's statistical data for displaying as graph over the given amount of past weeks starting today
 * @param int $number number of weeks
 * @return array structured result
 */
function getContactStatisticalDataLastWeeks($number)
{
    $stmt = "select count(R.id) as counter, DATE_FORMAT(`date`,'%Y-%m-%d') as time, A.name as type from `" . config::$SQL_PREFIX . "user_requests` as R left join `" . config::$SQL_PREFIX . "api-token` as A on R.module = A.id where `date` >= DATE(NOW()) - INTERVAL :number WEEK group by `date`,`type`;";
    return ExecuteStatisticStatement($stmt, $number);
}

/**
 * get's statistical data for displaying as graph over the given amount of past month starting today
 * @param int $number number of month
 * @return array structured result
 */
function getContactStatisticalDataLastMonth($number)
{
    $stmt = "select count(R.id) as counter, DATE_FORMAT(`date`,'%Y-%m-%d') as time, A.name as type from `" . config::$SQL_PREFIX . "user_requests` as R left join `" . config::$SQL_PREFIX . "api-token` as A on R.module = A.id where `date` >= DATE(NOW()) - INTERVAL :number MONTH group by `date`,`type`;";
    return ExecuteStatisticStatement($stmt, $number);
}

/**
 * get's statistical data for displaying as graph over the given amount of past year starting today
 * @param int $number number of years
 * @return array structured result
 */
function getContactStatisticalDataLastYear($number)
{
    $stmt = "select count(R.id) as counter, DATE_FORMAT(`date`,'%Y-%m-%d') as time, A.name as type from `" . config::$SQL_PREFIX . "user_requests` as R left join `" . config::$SQL_PREFIX . "api-token` as A on R.module = A.id where `date` >= DATE(NOW()) - INTERVAL :number YEAR group by `date`,`type`;";
    return ExecuteStatisticStatement($stmt, $number);
}

/**
 * get's statistical data for displaying as graph over the given amount of past days starting today
 * @param int $number number of days
 * @return array structured result
 */
function getContactStatisticalDataLastDays($number)
{
    $stmt = "select count(R.id) as counter, DATE_FORMAT(`date`,'%Y-%m-%d') as time, A.name as type from `" . config::$SQL_PREFIX . "user_requests` as R left join `" . config::$SQL_PREFIX . "api-token` as A on R.module = A.id where `date` >= DATE(NOW()) - INTERVAL :number DAY group by `date`,`type`;";
    return ExecuteStatisticStatement($stmt, $number);
}