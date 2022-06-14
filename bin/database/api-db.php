<?php
/**
 * This File includes all needed functions for api-token-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * gets a list of all API-Tokens for other applications
 * @return array structured result data
 */
function getAllApiTokens()
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'api-token` ;';
    $params = array();
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}

/**
 * Get complete Data for all Api-tokens, in structured array
 * @return array structured result, tokens are keys of array
 */
function getAllApiTokensOrderedByToken()
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'api-token` ;';
    $params = array();
    $resultZ = ExecuteStatementWR($prep_stmt, $params);
    $result = array();
    foreach ($resultZ as $entry) {
        $result[$entry['token']] = array(
            "name" => $entry["name"],
            "id" => $entry["id"]
        );
    }
    return $result;
}

/**
 * adds new api to token
 * @param array $result structured information to add new application
 * @return array state of request and token
 */
function addApiToken($result)
{
    $tokens = getAllApiTokens();
    $newtoken = generateRandomString(32);
    $existend = false;
    do {
        $existend = false;
        $newtoken = generateRandomString(32);
        foreach ($tokens as $token) {
            if ($newtoken === $token['token']) {
                $existend = true;
            }
        }
    } while ($existend);
    $prep_stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'api-token` ( `token` , `name` , `apiuri` ) VALUES ( ? , ? , ? );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $newtoken;
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $result['name'];
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $result['rapi'];
    $result = ExecuteStatementWOR($prep_stmt, $params);
    return array("result" => $result, "token" => $newtoken);
}

/**
 * deletes api-data from database
 * @param string $token of api which is going to be deleted
 */
function deleteApiToken($token)
{
    $prep_stmt = "DELETE FROM `" . config::$SQL_PREFIX . "api-token` WHERE `token` = :token";
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":token";
    dump($params, 8);
    ExecuteStatementWR($prep_stmt, $params, false);
    return;
}

/**
 * gets a list of all API-Tokens for other applications
 * @param int $id identifier if api
 * @return array structured result data
 */
function getApiTokenById($id)
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'api-token` where id = :id ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $id;
    $params[0]['nam'] = ":id";
    $result = ExecuteStatementWR($prep_stmt, $params)[0];
    return $result;
}

/**
 * updates data of a certain module
 * @param int $id identifier of module
 * @param string $name name of module
 * @param string $url url of reverse api of module
 */
function updateApiData($id, $name, $url)
{
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'api-token` SET `name` = :name , apiuri = :apiuri WHERE `id` = :id ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $name;
    $params[0]['nam'] = ":name";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $url;
    $params[1]['nam'] = ":apiuri";
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $id;
    $params[2]['nam'] = ":id";
    ExecuteStatementWR($prep_stmt, $params, false);
}