<?php
/**
 * This File includes all basic functions for database interaction
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * Generates PDO to access database
 * @param bool $additionalParams aktivate use of additional params
 * @return PDO PDO which is used to access database
 */
function getPdo($additionalParams = false)
{
    // PDO::ATTR_EMULATE_PREPARES => false to get normal values ; must check code first
    $OPTIONS = array(
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    );
    if ($additionalParams){
        return new PDO('mysql:host=' . config::$SQL_SERVER . ';dbname=' . config::$SQL_SCHEMA . ';charset=utf8', config::$SQL_USER, config::$SQL_PASSWORD, $OPTIONS);
    }
    return new PDO('mysql:host=' . config::$SQL_SERVER . ';dbname=' . config::$SQL_SCHEMA . ';charset=utf8', config::$SQL_USER, config::$SQL_PASSWORD);
}

/**
 * Executes Statement without read
 * @param string $prep_stmt Prepared SQL statement, with questionnaire
 * @param array $params params for prepared statement, need correct order to match questionnaire in prepared statement
 * @param bool $additionalParams aktivate use of additional params
 * @return bool|null state after preparing and executing statement
 */
function ExecuteStatementWOR($prep_stmt, $params = array(), $additionalParams = false)
{
    dump($prep_stmt, 7);
    dump($params, 7);
    if (config::$SQL_Connector == "pdo") {
        $pdo = getPdo($additionalParams);
        $sql = $pdo->prepare($prep_stmt);
        if (count($params) > 0) {
            for ($i = 0; $i < count($params); $i++) {
                $sql->bindParam($i + 1, $params[$i]['val']);
            }
        }
        $result = $sql->execute();
        dump($result, 7);
        return $result;
    }
    return null;
}

/**
 * Executes statement with read if selected
 * @param string $prep_stmt Prepared SQL statement, with questionnaire or specific placeholder
 * @param array $params structured data for placeholder or questionnaire in statement
 * @param bool $read selects if data is read from database, defaults to true
 * @param bool $disableNull Selects if a given parameter can be casted to 'NULL' or not.
 * @param bool $additionalParams aktivate use of additional params
 * @return array|void|null|bool state after preparing and executing statement or result of database request, depending on $read
 */
function ExecuteStatementWR($prep_stmt, $params, $read = true, $disableNull = true, $additionalParams = false)
{
    dump($prep_stmt, 7);
    if (config::$SQL_Connector == "pdo") {
        $pdo = getPdo($additionalParams);
        $sql = $pdo->prepare($prep_stmt);
        if (count($params) > 0) {
            for ($i = 0; $i < count($params); $i++) {
                if ($params[$i]['val'] == "" && $disableNull == false) {
                    $sql->bindValue($params[$i]['nam'], null, PDO::PARAM_NULL);
                    dump("Set NUll.", 8);
                } else {
                    $sql->bindParam($params[$i]['nam'], $params[$i]['val']);
                }
            }
        }
        $sql->execute();
        if ($read) {
            $result = $sql->fetchAll();
            dump($result, 7);
            return $result;
        }
        return;
    }
    return null;
}