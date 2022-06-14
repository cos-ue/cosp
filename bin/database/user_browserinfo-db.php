<?php
/**
 * This File includes all needed functions for storing the 
 * browserinfos of the user
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/** 
 * adds browserinformation to database
 * @return array|void|null structured result
 */
function insertBrowserData($browserName, $browserVersion, $plattform, $userAgent, $realBrowserAgent)
{
    //SQL Operation, with neccesary values
    $stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'user_browserinfo` ( `browserName` , `browserVersion` , `plattform` , `userAgent` , `realName` ) values ( :browserName , :browserVersion , :plattform , :userAgent , :realName );';
    //initialize of the insert array for the database
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $browserName;
    $params[0]['nam'] = ":browserName";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $browserVersion;
    $params[1]['nam'] = ":browserVersion";
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $plattform;
    $params[2]['nam'] = ":plattform";
    $params[3] = array();
    $params[3]['typ'] = 's';
    $params[3]['val'] = $userAgent;
    $params[3]['nam'] = ":userAgent";
    $params[4] = array();
    $params[4]['typ'] = 's';
    $params[4]['val'] = $realBrowserAgent;
    $params[4]['nam'] = ":realName";
    $result = ExecuteStatementWR($stmt,$params,false);
    return $result;
}