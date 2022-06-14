<?php
/**
 * This file contains all functions for management api, which is used by COSP-frontend
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * changes role of user
 * @param array $json structured container for needed information (new rank and username)
 * @return array input data extended with 'success'-entry
 */
function changeRole($json)
{
    if (($_SESSION['role'] >= config::$ROLE_EMPLOYEE) == false) {
        return generateFalse("Permission denied.");
    }
    if ($_SESSION['name'] == $json['username']) {
        return generateFalse("Permission denied.");
    }
    $allroles = getAllRolles();
    $usernames = getAllUsernames();
    $rolecheck = false;
    $usercheck = false;
    foreach ($allroles as $role) {
        if ($role['id'] == $json['role']) {
            $rolecheck = $role['value'] <= $_SESSION['role'];
            break;
        }
    }
    if (in_array($json['username'], $usernames)) {
        $usercheck = true;
    }
    if (($usercheck && $rolecheck) == false) {
        return generateFalse(array("requestData" => $json, "errormsg" => "Wrong Username or RoleID."));
    }
    updateRoleUser($json['username'], $json['role']);
    $json['success'] = true;
    return $json;
}

/**
 * generates an error state as array
 * @param array|string|bool|int $json data which should additional send back, default: empty array
 * @return array input data with additional 'reason' und 'success' fields
 */
function generateFalse($json = array())
{
    return array(
        "payload" => $json,
        "success" => false
    );
}

/**
 * adds new role to database
 * @param array $json needed data as array like name and value
 * @return array result as array state
 */
function addNewRole($json)
{
    if (($_SESSION['role'] >= config::$ROLE_ADMIN) == false) {
        return generateFalse("Permission denied.");
    }
    $result = addRole($json['value'], $json['name']);
    return generateSuccessMAPI($result);
}

/**
 * generates an success state as array with additional payload
 * @param array|int|bool|string $json optional payload
 * @return array success state as array
 */
function generateSuccessMAPI($json = array())
{
    return array(
        "payload" => $json,
        "success" => true
    );
}

/**
 * save edited roles from ui
 * @param array $json structured input data
 * @return array success state as array
 */
function saveEditRoleMapi($json)
{
    if (($_SESSION['role'] >= config::$ROLE_ADMIN) == false) {
        return generateFalse("Permission denied.");
    }
    if (checkRoleID($json['id'])) {
        updateRole($json['id'], $json['value'], $json['name']);
        return generateSuccessMAPI();
    }
    return generateFalse("Role not changed");
}

/**
 * deletes an exiting role
 * @param int $id roleid
 */
function deleteRoleMapi($id)
{
    if (($_SESSION['role'] >= config::$ROLE_ADMIN) == false) {
        return generateFalse("Permission denied.");
    }
    if (checkRoleID($id)) {
        deleteRole($id);
        return generateSuccessMAPI("Role successfully deleted");
    }
    return generateFalse("Role not deleted");
}

/**
 * updates userpassword
 * @param array $json array with id, pwd1 and pwd2
 * @return array state of success
 */
function changeUserPasswordMapi($json)
{
    if (($_SESSION['role'] >= config::$ROLE_EMPLOYEE) == false) {
        return generateFalse("Permission denied.");
    }
    if (in_array($json['id'], getAllUserIds())) {
        $check = inspectPassword($json['pwd1'], $json['pwd2']);
        if ($check) {
            updateUserPassword($json['id'], $json['pwd1']);
            return generateSuccessMAPI("Successfully updated Password!");
        }
    }
    return generateFalse("Error while updating password");
}

/**
 * toggles enable or disable state of useraccount
 * @param int $uid user which account should be toggled
 * @return array state of success
 */
function enableUserMAPI($uid)
{
    if (($_SESSION['role'] >= config::$ROLE_EMPLOYEE) == false) {
        return generateFalse("Permission denied.");
    }
    if (in_array($uid, getAllUserIds())) {
        $data = getUserDataByID($uid);
        if ($_SESSION['name'] == $data['name']) {
            return generateFalse("Permission denied.");
        }
        updateEnableUser($data['name'], ($data['enabled'] == 1 ? 0 : 1));
        $rapis = getAllApiTokens();
        foreach ($rapis as $rapi) {
            AktivationRemoteUser($rapi['apiuri'], $rapi['token'], $data['name'], $data['enabled'] == 1 ? 1 : 0);
        }
        return generateSuccessMAPI("Successfully updated User!");
    }
    return generateFalse("Error while updating User");
}

/**
 * reset User Password via MAPI
 * @param string $uid Userid
 * @return array success state
 */
function resetUserPasswordMAPI($uid)
{
    if (($_SESSION['role'] >= config::$ROLE_EMPLOYEE) == false) {
        return generateFalse("Permission denied.");
    }
    if (in_array($uid, getAllUserIds())) {
        $data = getUserDataByID($uid);
        PasswordResetViaMail($data['name'], $data['email']);
        return generateSuccessMAPI();
    }
    return generateFalse("Error while sending Mail to User");
}

/**
 * adds new role to database
 * @param array $json needed data as array like name and value
 * @return array result as array state
 */
function addNewRank($json)
{
    if (($_SESSION['role'] >= config::$ROLE_ADMIN) == false) {
        return generateFalse("Permission denied.");
    }
    $result = addRank($json['value'], $json['name']);
    return generateSuccessMAPI($result);
}

/**
 * deletes an exiting rank
 * @param int $id roleid
 */
function deleteRankMapi($id)
{
    if (($_SESSION['role'] >= config::$ROLE_ADMIN) == false) {
        return generateFalse("Permission denied.");
    }
    if (checkRankID($id)) {
        deleteRank($id);
        return generateSuccessMAPI("Rank successfully deleted");
    }
    return generateFalse("Rank not deleted");
}

/**
 * save edited roles from ui
 * @param array $json structured input data
 * @return array success state as array
 */
function saveEditRankMapi($json)
{
    if (($_SESSION['role'] >= config::$ROLE_ADMIN) == false) {
        return generateFalse("Permission denied.");
    }
    if (checkRankID($json['id'])) {
        updateRank($json['id'], $json['value'], $json['name']);
        return generateSuccessMAPI();
    }
    return generateFalse("Rank not changed");
}

/**
 * transforms data in correkt format for javascript
 * @param array $json structured query information
 * @return array structured result data
 */
function getStatisticalDataAPI($json)
{
    $result = "";
    if ($_SESSION['role'] < config::$ROLE_EMPLOYEE) {
        return generateErrorMAPI();
    }
    switch ($json['data']['src']) {
        case 'login':
            $result = loginStatistics($json['data']);
            break;
        case 'user':
            $result = NewUsersStatistics($json['data']);
            break;
        case 'pics':
            $result = getStatistics($json['data'], "pic");
            break;
        case 'story':
            $result = getStatistics($json['data'], 'story');
            break;
        case 'contact':
            $result = getStatistics($json['data'], 'contact');
            break;

    }
    return array_merge(generateSuccess(), array('data' => $result));
}

/**
 * gets all role names from DB
 * @return array structured result data
 */
function getAllRoleNamesAPI()
{
    $Roles = getAllRolles();
    $result = array();
    foreach ($Roles as $Role) {
        $result[] = $Role['name'];
    }
    return array_merge(generateSuccess(), array('data' => $result));
}

/**
 * gets all rank names from DB
 * @return array structured result data
 */
function getAllRankNamesAPI()
{
    $Ranks = getRanks();
    $result = array();
    foreach ($Ranks as $rank) {
        $result[] = $rank['name'];
    }
    return array_merge(generateSuccess(), array('data' => $result));
}

/**
 * generates captcha and structures the result
 * @return array structured result
 */
function generateCaptchaAPI()
{
    $image = generateCaptcha();
    $_SESSION['captcha'] = $image['code'];
    return array_merge(array('data' => $image['captcha']), generateSuccessMAPI());
}

/**
 * sends a userdefined contact message to admins and employee
 * @param array $json structured request
 */
function sendContactMessage($json)
{
    if ($json['cap'] === $_SESSION['captcha']) {
        if ($json['msg'] == "" || $json['msg'] == null || $json['title'] == null || $json['title'] == "") {
            $result = array(
                "msg" => $json['msg'] == "" || $json['msg'] == null,
                "title" => $json['title'] == null || $json['title'] == "",
                "cap" => false
            );
            return generateFalse($result);
        }
        $template = MailTemplates::ZentralMailContact($_SESSION['name'], $json['msg']);
        sendMail(config::$ZENTRAL_MAIL, $json['title'], $template, true, $_SESSION['email'], true);
        insertLogContactMail(getUserIp());
        unset($_SESSION['captcha']);
        return generateSuccessMAPI();
    }
    $result = array(
        "msg" => $json['msg'] == "" || $json['msg'] == null,
        "title" => $json['title'] == null || $json['title'] == "",
        "cap" => true
    );
    return generateFalse($result);
}

/**
 * get Modules withoput specific module base rang name
 * @param array $json structered input data
 * @return array structured result
 */
function getUnusedApisForRank($json)
{
    $rid = $json['id'];
    $ranknames = getNamesByRankId($rid);
    $apis = getAllApiTokens();
    $result = array();
    $apiIds = array();
    foreach ($ranknames as $rankname) {
        $apiIds[] = $rankname['aid'];
    }
    foreach ($apis as $api) {
        if (in_array($api['id'], $apiIds) == false) {
            $result[] = array(
                "name" => $api['name'],
                "id" => $api['id'],
            );
        }
    }
    return generateSuccessMAPI($result);
}

/**
 * get module-based rank names
 * @param array $json structured input data
 * @return array structured result
 */
function getModuleRankNames($json)
{
    $rid = $json['id'];
    $ranknames = getNamesByRankId($rid);
    $apis = getAllApiTokens();
    $apisSorted = array();
    foreach ($apis as $api) {
        $apisSorted[$api['id']] = $api['name'];
    }
    $result = array();
    foreach ($ranknames as $rankname) {
        $result[] = array(
            "id" => $rankname['id'],
            "rankname" => $rankname['name'],
            "modulename" => $apisSorted[$rankname['aid']],
        );
    }
    return generateSuccessMAPI($result);
}

/**
 * adds new module based name to rank
 * @param array $json structured input data
 * @return array structured result
 */
function insertModuleBasedRankNameMapi($json)
{
    if ($_SESSION['role'] < config::$ROLE_ADMIN) {
        return generateErrorMAPI();
    }
    insertModuleBasedRankName($json['rid'], $json['aid'], $json['name']);
    return generateSuccessMAPI();
}

/**
 * deletes module based rank name
 * @param array $json structured request data
 * @return array structured result data
 */
function delteModuleBasedRankNameMapi($json)
{
    if ($_SESSION['role'] < config::$ROLE_ADMIN) {
        return generateErrorMAPI();
    }
    $id = $json['id'];
    deleteModuleBasedRankName($id);
    return generateSuccessMAPI();
}

/**
 * replies with full data of api
 * @param array $json structured request data
 * @return array structured result data
 */
function getAllApiData($json)
{
    if ($_SESSION['role'] < config::$ROLE_ADMIN) {
        return generateErrorMAPI();
    }
    $data = getApiTokenById($json['id']);
    $result = array(
        'id' => $json['id'],
        "name" => $data['name'],
        "url" => $data['apiuri']
    );
    return array_merge(generateSuccessMAPI($result));
}

/**
 * saves edited module-api-data
 * @param array $json structured request data
 * @return array structured result data
 */
function saveApiDataAPI($json)
{
    if ($_SESSION['role'] < config::$ROLE_ADMIN) {
        return generateErrorMAPI();
    }
    updateApiData($json['id'], $json['name'], $json['url']);
    return generateSuccessMAPI();
}

function getModulBasedRights($json)
{
    $data = getAllModuleBasedRightsByUserID($json['id']);
    $result = array();
    foreach ($data as $date) {
        $result[] = array(
            "id" => $date['id'],
            "api" => $date['api'],
            "name" => $date['name'],
            "apiid" => $date['apiid'],
            "enabled" => $date['disabled'] == 0,
        );
    }
    return array_merge(generateSuccessMAPI($result));
}

/**
 * selects data for all apis with name and id
 * @return array structured result
 */
function getAllModules()
{
    $apis = getAllApiTokens();
    $result = array();
    foreach ($apis as $api) {
        $result[] = array(
            "name" => $api['name'],
            "id" => $api['id'],
        );
    }
    return generateSuccessMAPI($result);
}

/**
 * returns all allowed roles for current user (employee can't set admin-rights
 * @return array structured result
 */
function getSelectedRoles()
{
    $Roles = getAllRolles();
    $result = array();
    foreach ($Roles as $Role) {
        if ($_SESSION['role'] >= $Role['value']) {
            $result[] = array(
                "name" => $Role['name'],
                "id" => $Role['id'],
            );
        }
    }
    return generateSuccessMAPI($result);
}

/**
 * inserts new module based role for a certain user into database
 * @param array $json structured request
 * @return array structured result
 */
function addModuleRoleMapi($json)
{
    if ($_SESSION['role'] < config::$ROLE_EMPLOYEE) {
        return generateErrorMAPI();
    }
    $role = getRoleByID($json['role']);
    if ($_SESSION['role'] < $role['value']) {
        return generateErrorMAPI();
    }
    addModuleBasedRole($json['user'], $json['role'], $json['module']);
    return generateSuccessMAPI();
}

/**
 * generates an error state as array with additional payload
 * @param array|int|bool|string $json optional payload
 * @return array error state as array
 */
function generateErrorMAPI($json = array())
{
    return array(
        "payload" => $json,
        "success" => false
    );
}

/**
 * delete user module right
 * @param array $json structured request
 * @return array structured result
 */
function deleteModuleBasedRoleMapi($json)
{
    if ($_SESSION['role'] < config::$ROLE_EMPLOYEE) {
        return generateErrorMAPI();
    }
    $right = getModuleBasedRightsByID($json['id']);
    if ($_SESSION['role'] < $right['rolevalue']) {
        return generateErrorMAPI();
    }
    deleteModuleBasedRight($json['id']);
    return generateSuccess();
}

/**
 * sets state of mailvalidation
 * @param array $json structured requets
 * @return array structured result
 */
function setMailValidationValue($json)
{
    if ($_SESSION['role'] < config::$ROLE_EMPLOYEE) {
        return generateErrorMAPI();
    }
    $value = $json['state'] ? 1 : 0;
    updateMailValidated($json['name'], $value);
    return generateSuccess();
}

/**
 * updates module based rights in database
 * @param array $json structured request
 * @return array structured result
 */
function updateModuleRightsMapi($json)
{
    if ($_SESSION['role'] < config::$ROLE_EMPLOYEE) {
        $rightData = getModuleBasedRightsByID($json['rightid']);
        $ruid = $rightData['uid'];
        $apiid = $rightData['apiid'];
        $cuid = getUserData($_SESSION['name'])['id'];
        $rolesDB = getAllRolles();
        $roles = array();
        foreach ($rolesDB as $role) {
            $roles[$role['id']] = $role;
        }
        if (in_array($cuid, getPermissionUsersOfModule($apiid, $roles[$json['roleid']]['value'])) == false || $ruid == $cuid) {
            return generateErrorMAPI();
        }
    }
    $role = getRoleByID($json['roleid']);
    if ($_SESSION['role'] < $role['value']) {
        return generateErrorMAPI();
    }
    $rightData = getModuleBasedRightsByID($json['rightid']);
    $apiid = $rightData['apiid'];
    if ($rightData['rolevalue'] >= config::$ROLE_ADMIN && $_SESSION['role'] < config::$ROLE_ADMIN) {
        return generateFalse();
    }
    updateModulRights($json['rightid'], $json['roleid']);
    $link = "moduleRights.php?" . http_build_query(array('aid' => $apiid), '', '&');
    return generateSuccessMAPI($link);
}

/**
 * sets deaktivation state of module right
 * @param array $json structured request
 * @return array structured result
 */
function setDisableModuleRightStateMapi($json)
{
    if ($_SESSION['role'] < config::$ROLE_EMPLOYEE) {
        $rightData = getModuleBasedRightsByID($json['rightid']);
        $ruid = $rightData['uid'];
        $apiid = $rightData['apiid'];
        $cuid = getUserData($_SESSION['name'])['id'];
        $rolesDB = getAllRolles();
        $roles = array();
        foreach ($rolesDB as $role) {
            $roles[$role['id']] = $role;
        }
        if (in_array($cuid, getPermissionUsersOfModule($apiid, config::$ROLE_EMPLOYEE)) == false || $ruid == $cuid) {
            return generateFalse();
        }
    }
    $rightData = getModuleBasedRightsByID($json['rightid']);
    $apiid = $rightData['apiid'];
    $apiData = getApiTokenById($apiid);
    if ($rightData['rolevalue'] >= config::$ROLE_ADMIN && $_SESSION['role'] < config::$ROLE_ADMIN) {
        return generateFalse();
    }
    AktivationRemoteUser($apiData['apiuri'], $apiData['token'], $rightData['username'], $json['state'] ? 1 : 0);
    updateDisableStateModuleRight($json['rightid'], $json['state']);
    $link = "moduleRights.php?" . http_build_query(array('aid' => $apiid), '', '&');
    return generateSuccessMAPI($link);
}

/**
 * returns all users without rights on a given module
 * @param array $json structured request
 * @return array strucutred result
 */
function getUnsubscribedUserForModule($json)
{
    if ($_SESSION['role'] < config::$ROLE_EMPLOYEE) {
        $apiid = $json['module'];
        $cuid = getUserData($_SESSION['name'])['id'];
        $rolesDB = getAllRolles();
        $roles = array();
        foreach ($rolesDB as $role) {
            $roles[$role['id']] = $role;
        }
        if (in_array($cuid, getPermissionUsersOfModule($apiid, config::$ROLE_EMPLOYEE)) == false) {
            return generateFalse();
        }
    }
    $rights = getAllModuleBasedRightsByModulID($json['module']);
    $usersModule = array();
    foreach ($rights as $right) {
        $usersModule[] = $right['userid'];
    }
    $result = array();
    $users = getAllUsers();
    foreach ($users as $user) {
        if (in_array($user['id'], $usersModule) == false) {
            $result[] = array(
                "id" => $user['id'],
                "name" => $user['name'],
                "firstname" => $user['firstname'],
                "lastname" => $user['lastname']
            );
        }
    }
    return generateSuccessMAPI($result);
}

/**
 * adds new module to cosp
 * @param array $json structured request
 * @return array structured result
 */
function addNewModuleApi($json) {
    if ($_SESSION['role'] < config::$ROLE_ADMIN) {
        return generateErrorMAPI();
    }
    if ((isset($json['name']) === false) || (isset($json['url']) === false)) {
        return generateErrorMAPI();
    }
    $result = array();
    $result['name'] = $json['name'];
    $result['rapi'] = $json['url'];
    dump($result, 8);
    if (strpos($result['rapi'], "https://") !== false) {
        $result['rapi'] = str_replace("https://", "", $result['rapi']);
    }
    if (filter_var('https://' . $result['rapi'], FILTER_VALIDATE_URL) === false) {
        return generateErrorMAPI();
    }
    $token = addApiToken($result)['token'];
    return generateSuccessMAPI($token);
}

/**
 * checks if mailadress is in use already
 * @param array $json structured request
 * @return array strucutured result
 */
function checkMailAddressExistentAPI($json) {
    if (isset($json['debug_enable'])){
        if ($json['debug_enable'] == false) {
            $result = checkMailAddressExistent($json['mail'], false);
            return generateSuccessMAPI($result);
        }
    }
    $result = checkMailAddressExistent($json['mail']);
    return generateSuccessMAPI($result);
}

/**
 * checks if username is existent
 * @param array $json structured request
 * @return array strucutured result
 */
function checkMailUsernameExistentAPI($json) {
    $userExists = false;
    $usernames = getAllUsernames();
    if (in_array($json['username'], $usernames)) {
        $userExists = true;
    }
    return generateSuccessMAPI($userExists);
}

function insertBrowserDataAPI() {
    $browserinfo = detectBrowser();
    $result = insertBrowserData($browserinfo['name'], $browserinfo['version'], $browserinfo['platform'], $browserinfo['userAgent'], $browserinfo['hrname']);
    return generateSuccessMAPI($result);
}