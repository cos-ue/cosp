<?php
/**
 * This File includes all needed functions for statistics about pictures
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * get's statistical data for displaying as graph over the given amount of past weeks starting today
 * @param int $number number of weeks
 * @return array structured result
 */
function getNewPicturesStatisticalDataLastWeeks($number)
{
    $stmt = "select Count(P.token) as counter, DATE_FORMAT(`creationdate`,'%Y-%m-%d') as time, `name` as `type` from `" . config::$SQL_PREFIX . "pictures` as P join `" . config::$SQL_PREFIX . "api-token`  as A on P.aid = A.id where creationdate >= DATE(NOW()) - INTERVAL :number WEEK group by `time`, `type`;";
    return ExecuteStatisticStatement($stmt, $number);
}

/**
 * get's statistical data for displaying as graph over the given amount of past month starting today
 * @param int $number number of month
 * @return array structured result
 */
function getNewPicturesStatisticalDataLastMonth($number)
{
    $stmt = "select Count(P.token) as counter, DATE_FORMAT(`creationdate`,'%Y-%m-%d') as time, `name` as `type` from `" . config::$SQL_PREFIX . "pictures` as P join `" . config::$SQL_PREFIX . "api-token`  as A on P.aid = A.id where creationdate >= DATE(NOW()) - INTERVAL :number MONTH group by `time`, `type`;";
    return ExecuteStatisticStatement($stmt, $number);
}

/**
 * get's statistical data for displaying as graph over the given amount of past year starting today
 * @param int $number number of years
 * @return array structured result
 */
function getNewPicturesStatisticalDataLastYear($number)
{
    $stmt = "select Count(P.token) as counter, DATE_FORMAT(`creationdate`,'%Y-%m-%d') as time, `name` as `type` from `" . config::$SQL_PREFIX . "pictures` as P join `" . config::$SQL_PREFIX . "api-token`  as A on P.aid = A.id where creationdate >= DATE(NOW()) - INTERVAL :number YEAR group by `time`, `type`;";
    return ExecuteStatisticStatement($stmt, $number);
}

/**
 * get's statistical data for displaying as graph over the given amount of past days starting today
 * @param int $number number of days
 * @return array structured result
 */
function getNewPicturesStatisticalDataLastDays($number)
{
    $stmt = "select Count(P.token) as counter, DATE_FORMAT(`creationdate`,'%Y-%m-%d') as time, `name` as `type` from `" . config::$SQL_PREFIX . "pictures` as P join `" . config::$SQL_PREFIX . "api-token`  as A on P.aid = A.id where creationdate >= DATE(NOW()) - INTERVAL :number DAY group by `time`, `type`;";
    return ExecuteStatisticStatement($stmt, $number);
}