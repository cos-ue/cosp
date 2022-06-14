<?php
/**
 * This File contains all functions to creat or authenticate a user
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * creates new user
 * @param string $name username of new user
 * @param string $passwd plaintext password
 * @param string $email email address of new user
 * @param string $firstname first name of user, optional
 * @param string $lastname last name of user, optional
 */
function createNewUser($name, $passwd, $email, $firstname = "", $lastname = "")
{
    $pw_hash = password_hash($passwd, config::$PWD_ALGORITHM);
    addUser($name, $pw_hash, $email, $firstname, $lastname);
}

/**
 * updates a users password
 * @param int $uid users id
 * @param string $password plaintext new password
 */
function updateUserPassword($uid, $password)
{
    $pw_hash = password_hash($password, config::$PWD_ALGORITHM);
    updatePassword($uid, $pw_hash);
}

/**
 * checks if user used correct password
 * @param string $password password entered by user
 * @param string $username username of user
 * @return bool true if password is correct
 */
function checkPassword($password, $username)
{
    $userdata = getUserData($username);
    if (count($userdata) <= 0) {
        return false;
    }
    if ($userdata['enabled'] == false || $userdata['mailvalidated'] == false) {
        return false;
    }
    if($userdata['name'] !== $username) {
        return false;
    }
    $check = password_verify($password, $userdata['password']);
    if ($check) {
        dump($userdata, 8);
        $_SESSION['id'] = $userdata['id'];
        $_SESSION['name'] = $userdata['name'];
        $_SESSION['firstname'] = $userdata["firstname"];
        $_SESSION['lastname'] = $userdata["lastname"];
        $_SESSION['email'] = $userdata["email"];
        $role = getRoleByID($userdata['role']);
        $_SESSION['role'] = $role['value'];
        logLogin("user");
    }
    if ($userdata['enabled'] == false) {
        return false;
    }
    return $check;
}

/**
 * checks only password, is not setting _SESSION variable
 * @param string $username username whichs password should be checked
 * @param string $password oassword to check
 */
function checkPasswordOnly($username, $password){
    $userdata = getUserData($username);
    $check = password_verify($password, $userdata['password']);
    return $check;
}

/**
 * decides if password has the correct meta-parameter, e.g. length, and both values of the two input-boxes are the same
 * @param string $PasswordField1Val password from input field 1
 * @param string $PasswordField2Val password from input field 1
 * @return bool true if password passed the check
 */
function inspectPassword($PasswordField1Val, $PasswordField2Val)
{
    if ($PasswordField1Val !== $PasswordField2Val) {
        return false;
    }
    if (strlen($PasswordField1Val) < config::$PWD_LENGTH) {
        return false;
    }
    return true;
}

/**
 * updates Session Data of User
 * @param string $firstname firstname of user
 * @param string $lastname lastname of user
 * @param string $EMail E-Mailaddress of user
 */
function updateUser($firstname, $lastname, $EMail) {
    updateUserDB($firstname, $lastname, $EMail, $_SESSION['name']);
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['email'] = $EMail;
}

/**
 * Logs Login as Unique Visitor without logging username
 * @param string $type login type is guest or user
 */
function logLogin($type)
{
    insertLogUniqueVisitors(getUserIp(), $type);
}