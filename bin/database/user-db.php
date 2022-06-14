<?php
/**
 * This File includes all needed functions for user-login-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * Adds new User to database
 * @param string $name username of new user
 * @param string $pwd_hash hash of password of new user
 * @param string $email email of new user
 * @param string $firstname firstname of new user
 * @param string $lastname lastname of new user
 * @return bool|null returns result state
 */
function addUser($name, $pwd_hash, $email, $firstname = "", $lastname = "")
{
    if (checkMailAddressExistent($email)){
        return false;
    }
    $prep_stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'user-login` ( `name` , `password` , `firstname` , `lastname` , `email` , `role` ) VALUES ( ? , ? , ? , ? , ? , ? );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $name;
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $pwd_hash;
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $firstname;
    $params[3] = array();
    $params[3]['typ'] = 's';
    $params[3]['val'] = $lastname;
    $params[4] = array();
    $params[4]['typ'] = 's';
    $params[4]['val'] = $email;

    $roles = getAllRolles();
    $lrole = null;
    foreach ($roles as $role) {
        if (($lrole == null) || ($lrole['value'] > $role ['value'])) {
            $lrole = $role;
        }
    }

    $params[5] = array();
    $params[5]['typ'] = 's';
    $params[5]['val'] = $lrole['id'];
    $result = ExecuteStatementWOR($prep_stmt, $params);
    return $result;
}

/**
 * Gets userdata from database
 * @param int $ID unique Identifier of User
 * @return array|null|bool structured data of user or state of result if user not existent
 */
function getUserDataByID($ID)
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'user-login` where id = :id ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $ID;
    $params[0]['nam'] = ":id";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    if (count($result) > 0) {
        $result = $result[0];
    }
    dump($result, 7);
    return $result;
}

/**
 * Selects userdata based on username
 * @param string $name unique username of user
 * @return array|null|bool structured data of user or state of result if user not existent
 */
function getUserData($name)
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'user-login` where name = :name ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $name;
    $params[0]['nam'] = ":name";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    if (count($result) > 0) {
        $result = $result[0];
    }
    dump($result, 7);
    return $result;
}

/**
 * Selects userdata based on username
 * @param string $mail unique username of user
 * @return array|null|bool structured data of user or state of result if user not existent
 */
function getUserDataByMailadress($mail)
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'user-login` where email = :email ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $mail;
    $params[0]['nam'] = ":email";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    if (count($result) > 0) {
        $result = $result[0];
    }
    dump($result, 7);
    return $result;
}

/**
 * gets list of usernames which already are in use
 * @return array structured result data
 */
function getAllUsernames()
{
    $prep_stmt = 'select name from `' . config::$SQL_PREFIX . 'user-login` ;';
    $params = array();
    $result = ExecuteStatementWR($prep_stmt, $params);
    $tmp = array();
    foreach ($result as $res) {
        $tmp[] = $res[0];
    }
    dump($tmp, 7);
    return $tmp;
}

/**
 * get all user-identifier
 * @return array structured result data
 */
function getAllUserIds()
{
    $prep_stmt = 'select id from `' . config::$SQL_PREFIX . 'user-login` ;';
    $params = array();
    $result = ExecuteStatementWR($prep_stmt, $params);
    $tmp = array();
    foreach ($result as $res) {
        $tmp[] = $res[0];
    }
    dump($tmp, 7);
    return $tmp;
}

/**
 * updates validation state of user
 * @param string $name username whose email validation value is going to be changed
 * @param bool $status state which validation value should have afterwards
 */
function updateMailValidated($name, $status)
{
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'user-login` SET `mailvalidated` = :mailvalidated WHERE `' . config::$SQL_PREFIX . 'user-login`.`name` = :name ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $status;
    $params[0]['nam'] = ":mailvalidated";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $name;
    $params[1]['nam'] = ":name";
    ExecuteStatementWR($prep_stmt, $params, false);
}

/**
 * sets user enable state
 * @param string $name username whose enable validation value is going to be changed
 * @param bool $status state which enable value should have afterwards
 */
function updateEnableUser($name, $status)
{
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'user-login` SET `enabled` = :enabled WHERE `' . config::$SQL_PREFIX . 'user-login`.`name` = :name ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $status;
    $params[0]['nam'] = ":enabled";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $name;
    $params[1]['nam'] = ":name";
    ExecuteStatementWR($prep_stmt, $params, false);
}

/**
 * Gets all used usernames from database
 * @return array structured result
 */
function getAllUsers()
{
    $prep_stmt = 'select * from `' . config::$SQL_PREFIX . 'user-login` ;';
    $params = array();
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}

/**
 * changes password of user
 * @param int $uid unique Identifier of User
 * @param string $passwordHash hash of new password
 */
function updatePassword($uid, $passwordHash)
{
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'user-login` SET `password` = :password WHERE `' . config::$SQL_PREFIX . 'user-login`.`id` = :userid ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $passwordHash;
    $params[0]['nam'] = ":password";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $uid;
    $params[1]['nam'] = ":userid";
    ExecuteStatementWR($prep_stmt, $params, false);
}

/**
 * updates Role of user
 * @param string $name username which role will be changed
 * @param int $role approved unique rank identifier (Rank-ID)
 */
function updateRoleUser($name, $role)
{
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'user-login` SET `role` = :role WHERE `' . config::$SQL_PREFIX . 'user-login`.`name` = :name ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $role;
    $params[0]['nam'] = ":role";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $name;
    $params[1]['nam'] = ":name";
    ExecuteStatementWR($prep_stmt, $params, false);
}

/**
 * updates dúserdata in database
 * @param string $firstname firstname which should be in database
 * @param string $lastname lastname which should be in database
 * @param string $email email which should be in database
 * @param string $username username for which things are changed
 */
function updateUserDB($firstname, $lastname, $email, $username)
{
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'user-login` SET `firstname` = :firstname , `lastname` = :lastname , `email` = :email WHERE `' . config::$SQL_PREFIX . 'user-login`.`name` = :name ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $firstname;
    $params[0]['nam'] = ":firstname";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $lastname;
    $params[1]['nam'] = ":lastname";
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $email;
    $params[2]['nam'] = ":email";
    $params[3] = array();
    $params[3]['typ'] = 's';
    $params[3]['val'] = $username;
    $params[3]['nam'] = ":name";
    ExecuteStatementWR($prep_stmt, $params, false);
}