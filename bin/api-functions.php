<?php
/**
 * This File contains all functions which are called by module-api
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * Returns all Data for requested user
 * @param string $username username which data is requested
 * @param bool $ignoreDeaktiviation deaktiviates ignoration of locked users
 * @param string $apitoken identifier of api
 * @return array Sturctured array with requested data
 */
function getUserdataApi($username, $ignoreDeaktiviation, $apitoken)
{
    $Data = getUserData($username);
    if (count($Data) == 0) {
        return array(
            'existent' => 0,
            "result" => "ack",
            "code" => 0
        );
    }
    if (($Data['enabled'] == false || $Data['mailvalidated'] == false) && $ignoreDeaktiviation == false) {
        return array(
            'message' => 'user not enabled',
            'existent' => 0,
            "result" => "ack",
            "code" => 0,
        );
    }
    $role = getRoleByID($Data['role']);
    if (count($role) == 0) {
        return array(
            'existent' => 0,
            "result" => "ack",
            "code" => 0
        );
    }
    $role = array(
        "roleid" => $Data['role'],
        "rolevalue" => $role['value'],
        "rolename" => $role['name']
    );
    $moduleRights = getModuleRightByUsernameApiToken($username, $apitoken);
    if (count($moduleRights) > 0) {
        if ($moduleRights['disabled'] == 1) {
            return array(
                'message' => 'user not enabled',
                'existent' => 0,
                "result" => "ack",
                "code" => 0,
            );
        }
        if ($role['rolevalue'] < config::$ROLE_EMPLOYEE) {
            $role['roleid'] = $moduleRights['roleid'];
            $role['rolevalue'] = $moduleRights['rolevalue'];
            $role['rolename'] = $moduleRights['rolename'];
        }
    } else {
        if ($role['rolevalue'] < config::$ROLE_EMPLOYEE) {
            $AllRoles = getAllRolles();
            $rolevalues = array();
            foreach ($AllRoles as $oneRole) {
                if ($oneRole['value'] <= config::$ROLE_UNAUTH_USER) {
                    $rolevalues[] = $oneRole['value'];
                }
            }
            $selectedValue = max($rolevalues);
            foreach ($AllRoles as $oneRole) {
                if ($oneRole['value'] == $selectedValue) {
                    $role['roleid'] = $oneRole['id'];
                    $role['rolevalue'] = $oneRole['value'];
                    $role['rolename'] = $oneRole['name'];
                    $userid =  $Data['id'];
                    $appid = getAllApiTokensOrderedByToken()[$apitoken]['id'];
                    addModuleBasedRole($userid, $oneRole['id'], $appid);
                }
                break;
            }
        }
    }
    return array(
        'existent' => 1,
        'id' => $Data['id'],
        'username' => $Data['name'],
        'password' => $Data['password'],
        'firstname' => $Data['firstname'],
        'lastname' => $Data['lastname'],
        'email' => $Data['email'],
        'role' => $role,
        "result" => "ack",
        "code" => 0
    );
}

/**
 * Returns Roledata for given username
 * @param string $username username transmitted by requester
 * @return array structured array with result data
 */
function getRoledataApi($username)
{
    $Data = getUserData($username);
    if (count($Data) == 0) {
        return array(
            'existent' => 0
        );
    }
    $role = getRoleByID($Data['role']);
    if (count($role) == 0) {
        return array(
            'existent' => 0
        );
    }
    return array(
        'existent' => 1,
        'username' => $Data['name'],
        'role' => array(
            "roleid" => $Data['role'],
            "rolevalue" => $role['value'],
            "rolename" => $role['name'],
        ),
        "result" => "ack",
        "code" => 0
    );
}

/**
 * generates Json for API-Functions, uniform for all Functions of api.php
 * @param array $array Array which should be encoded
 */
function generateJson($array)
{
    echo json_encode($array);
}

/**
 * Generates Error-Result for functions, uniform for all Functions of api.php
 * @param string|int|array $input optional message to send with failed api-request
 * @return array structured array, which equals an error state
 */
function generateError($input = "")
{
    if ($input === "") {
        return array(
            "result" => "Wrong Request!",
            "code" => 1
        );
    }
    return array(
        "message" => $input,
        "result" => "Wrong Request!",
        "code" => 1
    );
}

/**
 * Generates Success-Result for functions, uniform for all Functions of api.php
 * @return array structured array, which equals an success state
 */
function generateSuccess()
{
    return array(
        "result" => "ack",
        "code" => 0
    );
}

/**
 * Adds points to a users account
 * @param string $username username, whose account value should be increased
 * @param string $token token of api-user which wants to add points to users account
 * @param string $reason reason for rank points
 * @param int $points value which should be added to account of user
 * @return array structured result state of request
 */
function addRankPoints($username, $token, $reason, $points)
{
    $rid = checkReasonExists($reason);
    if ($rid == false) {
        addReason($reason);
        $rid = checkReasonExists($reason);
        if ($rid == false) {
            ServerError();
        }
    }
    if (is_numeric($points) == false) {
        ServerError();
    }
    $points = intval($points);
    if (is_int($points) == false) {
        ServerError();
    }
    if (in_array($username, getAllUsernames()) == false) {
        ServerError();
    }
    $aid = getAllApiTokensOrderedByToken()[$token]['id'];
    $uid = getUserData($username)['id'];
    addPoints($uid, $aid, $points, $rid);
    return array(
        "result" => "ack",
        "code" => 0
    );
}

/**
 * selects all Rank points for a user for a certain application
 * @param string $username username whose account value is requested
 * @param string $token token of requesting application
 * @return array structured result state of request with requested data
 */
function getRankPoints($username, $token)
{
    if (in_array($username, getAllUsernames()) == false) {
        ServerError();
    }
    $aid = getAllApiTokensOrderedByToken()[$token]['id'];
    $uid = getUserData($username)['id'];
    $points = getPointsOfUser($uid, $aid)["SUM(`value`)"];
    if (is_numeric($points) == false) {
        $points = 0;
    }
    return array(
        "username" => $username,
        "points" => $points,
        "result" => "ack",
        "code" => 0
    );
}

/**
 * selects all Rank points earned in the last year (today - 1 Year) for a user for a certain application
 * @param string $username username whose account value is requested
 * @param string $token token of requesting application
 * @return array structured result state of request with requested data
 */
function getRankPointsLastYear($username, $token)
{
    if (in_array($username, getAllUsernames()) == false) {
        ServerError();
    }
    $aid = getAllApiTokensOrderedByToken()[$token]['id'];
    $uid = getUserData($username)['id'];
    $points = getPointsOfUserLastYear($uid, $aid)["SUM(`value`)"];
    if (is_numeric($points) == false) {
        $points = 0;
    }
    return array(
        "username" => $username,
        "points" => $points,
        "result" => "ack",
        "code" => 0
    );
}

/**
 * Generates a list for a certain application of its users
 * @param string $token token of requesting application
 * @return array structured result state of request with requested data
 */
function getRankLists($token)
{
    $aid = getAllApiTokensOrderedByToken()[$token]['id'];
    $List = getRanktableForApplication($aid);
    $result = array();
    foreach ($List as $l) {
        if (isStaff($l['name'])) {
            continue;
        }
        $result[$l['SUMTOTAL']] = array(
            "SUMTOTAL" => $l['SUMTOTAL'],
            "SUMYEAR" => $l['SUMYEAR'],
            "name" => $l['name']
        );
    }
    ksort($result);
    return array(
        "points" => $result,
        "result" => "ack",
        "code" => 0
    );
}

/**
 * returns all Usernames of COSP
 * @return array structured result state of request with requested data
 */
function getAllUsernamesApi()
{
    $Usernames = getAllUsernames();
    return array(
        "usernames" => $Usernames,
        "result" => "ack",
        "code" => 0
    );
}

/**
 * adds new User if remote application requested it
 * @param array $params structured array with needed data
 * @return array structured result state of request with supplied data (for checking correctness)
 */
function addUserdataRemote($params)
{
    addUser($params["username"], $params["password"], $params["email"], $params["firstname"], $params["lastname"]);
    if (count(getUserData($params["username"])) <= 0) {
        return array(
            "result" => "nack",
            "code" => 1,
            "Post" => $_POST,
            "test" => false
        );
    }
    $registrationCompletionLink = generateValidateableLink($params["username"], "validate.php");
    sendMail($params["email"], "Registration des Nutzers " . $params["username"], MailTemplates::RegisterMail($registrationCompletionLink, $params["username"]), true);
    sendMail(config::$ZENTRAL_MAIL, "Registration des Nutzers " . $params["username"], MailTemplates::ZentralRegisterMail($params["username"]), true);
    return array(
        "result" => "ack",
        "code" => 0,
        "Post" => $_POST,
        "test" => true
    );
}

/**
 * adds picture from remote application to datasource
 * @param array $params structured array with needed data
 * @return array structured result state of request with additional data
 */
function UploadPicture($params)
{
    $title = $params['title'];
    $description = $params['desc'];
    $uploads_dir = config::$UPLOAD_DIR;
    $params['aid'] = getAllApiTokensOrderedByToken()[$params['token']]['id'];
    $fileName = "";
    $token = "";
    if ($_FILES["pic"]["error"] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["pic"]["tmp_name"];
        $name = basename($_FILES["pic"]["name"]);
        if(function_exists('getimagesize')) {
            if(!@is_array(getimagesize($tmp_name))){
                return array(
                    "result" => "nack",
                    "code" => 1
                );
            }
        }
        move_uploaded_file($tmp_name, "$uploads_dir/$name");
        $newName = hash_file('sha512', "$uploads_dir/$name");
        $path_parts = pathinfo($name);
        $newName = $newName . time();
        $token = $newName;
        $fileName = $newName . "." . $path_parts['extension'];
        $newName = $uploads_dir . "/" . $fileName;
        rename("$uploads_dir/$name", $newName);
    } else {
        return array(
            "result" => "nack",
            "code" => 1
        );
    }
    $base64Thumb = createThumbnail($newName);
    $Userdata = getUserData($params['username']);
    insertPic($title, $description, $fileName, $base64Thumb, $token, $Userdata['id'], $params['aid']);
    if (isset($params['source'], $params['sourceid'])) {
        updateMaterialSourceByToken($token, $params['source'], $params['sourceid']);
    }
    $result = array(
        "title" => $title,
        "desc" => $description,
        "token" => $token,
        "result" => "ack",
        "code" => 0
    );
    return $result;
}

/**
 * generates data for a secured Link to display pictures
 * @param string $pictureToken token of picture for which data is requested
 * @param string $apptoken token from requesting application
 * @param string $username username of user for which data is requested
 * @param int $rankValue rank value of user
 * @return array structured result state of request with requested data
 */
function getSeccode($pictureToken, $apptoken, $username, $rankValue)
{
    $seccode = generateValidatableDataMaterial($pictureToken . ";" . $username . ";" . $rankValue . ";" . hashString($apptoken));
    $result = array_merge($seccode, generateSuccess());
    return $result;
}

/**
 * Generates List of available Pictures for certain application
 * @param string $apptoken token from requesting application
 * @param string $username username of user for which data is requested
 * @param string $rankValue rank value of user
 * @return array structured result state of request with requested data
 */
function getPictureList($apptoken, $username, $rankValue)
{
    $pil = getPictureListDB($apptoken);
    $cleanedpil = array();
    foreach ($pil as $p) {
        $cleanedpil[] = array(
            "id" => $p['id'],
            "identifier" => $p['token'],
            "token" => generateValidatableDataMaterial($p['token'] . ";" . $username . ";" . $rankValue . ";" . hashString($apptoken)),
            "title" => $p['title'],
            "description" => $p['description'],
            "username" => getUserDataByID($p['uid'])['name'],
            "validationValue" => getValidateSumPictures($p['id']),
            "valUsers" => getUservalidationsPictures($p['id']),
            "deleted" => $p['deleted'],
            "sourcename" => $p['sourcename'],
            "source" => $p['source'],
            "sourceid" => $p['sourceid'],
        );
    }
    $result = array(
        "pics" => $cleanedpil,
        "result" => "ack",
        "code" => 0
    );
    return $result;
}

/**
 * Returns all available Ranks
 * @param string $token alphanumerical identifier of module
 * @return array structured result state of request with requested data
 */
function getRankTypes($token)
{
    $rt = getRanks();
    $apis = getAllApiTokensOrderedByToken();
    $mr = getRankNamesByModule($apis[$token]['id']);
    $rolenames = array();
    foreach ($mr as $m) {
        $rolenames[$m['rid']] = $m['name'];
    }
    $rt2 = array();
    foreach ($rt as $r) {
        $rt2[$r['value']] = array(
            'value' => $r['value'],
            'name' => isset($rolenames[$r['id']]) ? $rolenames[$r['id']] : $r['name'],
            'image' => "data:image/svg+xml;base64," . base64_encode(file_get_contents("images/" . $r['icon']))
        );
    }
    ksort($rt2);
    $result = array(
        "ranktypes" => $rt2,
        "result" => "ack",
        "code" => 0
    );
    return $result;
}

/**
 * Adds Story to local story database
 * @param array $json structured array with needed information
 * @return array structured result state of request with additional data
 */
function uploadStory($json)
{
    $Userdata = getUserData($json['username']);
    $json['uid'] = $Userdata['id'];
    $json['hash'] = hashString($json['story']);
    $json['aid'] = getAllApiTokensOrderedByToken()[$json['token']]['id'];
    insertStory($json);
    unset($json['uid']);
    return array_merge(array('data' => array(
        "username" => $json['username'],
        "title" => $json['title'],
        "story" => $json['story'],
        "type" => $json['type'],
        "token" => $json['token']
    ), 'token' => $json['hash']), generateSuccess());
}

/**
 * Creates Linkdata to download one Story from Server
 * @param array $json structured array with all needed Information
 * @return array structured result state of request with requested data
 */
function getUserStories($json)
{
    $storietoken = getStoriesByApp(getAllApiTokensOrderedByToken()[$json['token']]['id']);
    $username = $json['username'];
    $cleanedlist = array();
    foreach ($storietoken as $p) {
        $usernamestory = getUserStory($p['storie_token'])[0]['name'];
        $storyId = getStoryIdByToken($p['storie_token']);
        $cleanedlist[] = array(
            "token" => generateValidatableDataMaterial($p['storie_token'] . ";" . $username . ";" . $json['rank-val'] . ";" . hashString($json['token'])),
            "username" => getUserDataByID($p['user_id'])['name'],
            "validated" => ((getValidateSumStories($storyId) >= 400) || ($usernamestory == $username)) ? true : false,
        );
    }
    return array_merge(array('data' => $cleanedlist), generateSuccess());
}

/**
 * Creates Linkdata to download all Stories from Server
 * @param array $json structured array with all needed Information
 * @return array structured result state of request with requested data
 */
function getAllUserStories($json)
{
    $storietoken = getStoriesByApp(getAllApiTokensOrderedByToken()[$json['token']]['id']);
    $username = $json['username'];
    $allValidationStates = false;
    if (isset($json['unvalidated'])) {
        $allValidationStates = $json['unvalidated'];
    }
    $nonapproved = false;
    if (isset($json['nonapproved'])) {
        $nonapproved = $json['nonapproved'];
    }
    $deleted = false;
    if (isset($json['deleted'])) {
        $deleted = $json['deleted'];
    }
    $token = "";
    foreach ($storietoken as $p) {
        if ($deleted || $p['deleted'] == 0) {
            if (($nonapproved || getStoryApprovalByStoryToken($p['storie_token'])) && ($allValidationStates || getValidateSumStories(getStoryIdByStoryToken($p['storie_token'])) >= 400)) {
                if ($token === "") {
                    $token = $p['storie_token'] . ";" . $username . ";" . $json['rank-val'] . ";" . hashString($json['token']);
                } else {
                    $token .= ',' . $p['storie_token'] . ";" . $username . ";" . $json['rank-val'] . ";" . hashString($json['token']);
                }
            }
        }
    }
    if ($token === "") {
        return array_merge(array('data' => array()), generateSuccess());
    }
    return array_merge(array('data' => generateValidatableDataMaterial($token)), generateSuccess());
}

/**
 * returns structured data to load a single picture
 * @param string $apptoken application token of requesting application
 * @param string $username username for which data is requested
 * @param int $rankValue value of rank for given user
 * @param string $pictureToken unique identifier of picture
 * @return array structured result state of request with requested data
 */
function getSinglePicture($apptoken, $username, $rankValue, $pictureToken)
{
    $pil = getSinglePictureFromDB($apptoken, $pictureToken);
    $cleanedpil = array(
        "id" => $pil['id'],
        "token" => generateValidatableDataMaterial($pil['token'] . ";" . $username . ";" . $rankValue . ";" . hashString($apptoken)),
        "title" => $pil['title'],
        "description" => $pil['description'],
        "username" => getUserDataByID($pil['uid'])['name'],
        "validationValue" => getValidateSumPictures($pil['id']),
        "valUsers" => getUservalidationsPictures($pil['id']),
        "deleted" => $pil['deleted'],
        "sourcename" => $pil['sourcename'],
        "source" => $pil['source'],
        "sourceid" => $pil['sourceid'],
    );
    $result = array(
        "pic" => $cleanedpil,
        "result" => "ack",
        "code" => 0
    );
    return $result;
}

/**
 * Saves changed Data for Picture
 * @param string $token token of picture which data is going to be change
 * @param string $title changed title of picture
 * @param string $description changed description of picture
 * @return array structured result state, state will always be true
 */
function saveSinglePictureMaterial($json)
{
    $token = $json['pictureToken'];
    $title = $json['title'];
    $description = $json['description'];
    updateMaterialByToken($token, $title, $description);
    if (isset($json['source'], $json['sourceid'])) {
        $source = $json['source'];
        $sourceTypeId = $json['sourceid'];
        updateMaterialSourceByToken($token, $source, $sourceTypeId);
    }
    return generateSuccess();
}

/**
 * Saves changed Data for Story
 * @param string $token token of Story which data is going to be change
 * @param string $title changed title of story
 * @param string $story changed narrative of story
 * @return array structured result state, state will always be true
 */
function EditStoryAPI($token, $title, $story)
{
    updateStorieByToken($token, $title, $story);
    return generateSuccess();
}

/**
 * Send User Mail to reset password
 * @param array $json structured array with all needed Information
 */
function resetUserPasswordViaAPI($json)
{
    if (in_array($json['username'], getAllUsernames())) {
        $data = getUserData($json['username']);
        PasswordResetViaMail($data['name'], $data['email']);
        return generateSuccess();
    }
    return generateError("Error while sending Mail to User");
}

/**
 * deletes picture from database
 * @param array $json structured array with all needed Information
 * @return array state of request
 */
function deletePictureApi($json)
{
    deletePicByTokenDBWrap($json['token'], $json['pictureToken']);
    return generateSuccess();
}

/**
 * sends multiple stories as array
 * @param array $json structured required information
 * @return array result of request
 */
function getStoriesAsListByListApi($json)
{
    $list = $json['tokenList'];
    $return = array();
    foreach ($list as $entry) {
        $Story = getUserStory($entry)[0];
        $return[$entry] = array(
            'token' => $entry,
            'story' => $Story['story'],
            'title' => $Story['title'],
            'name' => $Story['name'],
            'date' => $Story['date'],
            'validate' => getValidateSumStories($Story['StoryId']) >= 400,
        );
    }
    $result = array(
        "data" => $return,
        "result" => "ack",
        "code" => 0
    );
    return $result;
}

/**
 * sends multiple story title and token as array
 * @param array $json structured required Information
 * @return array result of request
 */
function getStoryTitleAsListByListApi($json)
{
    $aid = getAllApiTokensOrderedByToken()[$json['token']]['id'];
    $stories = getStoriesByApp($aid);
    $result = array();
    foreach ($stories as $story) {
        $result[] = array(
            "title" => $story['title'],
            "token" => $story['storie_token']
        );
    }
    return array_merge(generateSuccess(), array("data" => $result));
}

/**
 * safely deletes a users story
 * @param array $json structured required information
 * @return array result of request, will always be true
 */
function deleteUserStoryApi($json)
{
    if (isset($json['data']['story_token']) == false) {
        return generateError('st');
    }
    if (isset($json['data']['admin']) == false) {
        return generateError('adm');
    }
    if (isset($json['data']['user']) == false) {
        return generateError('usr');
    }
    if (getModuleTokenByStoryToken($json['data']['story_token']) == $json['token']) {
        if ($json['data']['admin'] == true || getCreatorByStoryToken($json['data']['story_token']) == $json['data']['user']) {
            deleteStoryByTokenDBWrap($json['data']['story_token']);
            return generateSuccess();
        }
    }
    return generateError('false');
}

/**
 * sets approvemnt to a certain storie if user is admin or employee
 * @param array $json structured request data
 * @return array structured result, will fail if user is not admin
 */
function approveUserStorie($json)
{
    $user = getUserData($json['username']);
    $role = getRoleByID($user['role']);
    if ($role['value'] >= config::$ROLE_EMPLOYEE) {
        changeStoryApproval($json['storie_token'], 1);
    } else {
        return generateError();
    }
    return generateSuccess();
}

/**
 * sets disapprovemnt to a certain storie if user is admin or employee
 * @param array $json structured request data
 * @return array structured result, will fail if user is not admin
 */
function disapproveUserStorie($json)
{
    $user = getUserData($json['username']);
    $role = getRoleByID($user['role']);
    if ($role['value'] >= config::$ROLE_EMPLOYEE) {
        changeStoryApproval($json['storie_token'], 0);
    } else {
        return generateError();
    }
    return generateSuccess();
}

/**
 * sends a contact mail to admins
 * @param array $json structured request
 * @return array structured result (will always be true)
 */
function sendContactMail($json)
{
    $moduleName = getAllApiTokensOrderedByToken()[$json['token']]['name'];
    $template = MailTemplates::ZentralMailContact($json['username'], $json['msg']);
    sendMail($json['receiver'], $moduleName . ": " . $json['title'], $template, true, $json['email'], true);
    $apis = getAllApiTokensOrderedByToken()[$json['token']];
    insertLogContactMail($json['ip'], $apis['id']);
    return generateSuccess();
}

/**
 * send a capture code to a asking Module
 * @param array $json structured requests
 * @return array structured result
 */
function getCaptchaAPI($json)
{
    $captchaArray = generateCaptcha($json['special'], true);
    return array_merge(array('data' => $captchaArray), generateSuccess());
}

/**
 * restores a story
 * @param array $json structured request
 * @return array structured result
 */
function RestoreStoryAPI($json)
{
    restoreStoryReference($json['IDent']);
    updateDeletionStateStoryByToken($json['IDent'], false);
    return generateSuccess();
}

/**
 * final deletion of a story
 * @param array $json structured request
 * @return array structured result
 */
function FinalDeleteStory($json)
{
    deleteStoryByTokenDBWrap($json['IDent'], true);
    return generateSuccess();
}

/**
 * restores a picture
 * @param array $json structured request
 * @return array structured result
 */
function RestorePictureAPI($json)
{
    $LinkID = getSinglePictureFromDB($json['token'], $json['IDent'])['id'];
    restorePictureReferences($json['IDent']);
    updateDeletionStatePictureByToken($LinkID, false);
    return generateSuccess();
}

/**
 * final deletion of a picture
 * @param array $json structured request
 * @return array structured result
 */
function FinalDeletePicture($json)
{
    deletePicByTokenDBWrap($json['token'], $json['IDent'], true);
    return generateSuccess();
}

/**
 * gets all types of sources
 * @return array structured result
 */
function getSourceTypesAPI()
{
    $sources = getAllSourceTypes();
    $result = array();
    foreach ($sources as $source) {
        $result[] = array(
            "id" => $source['id'],
            "name" => $source['name'],
        );
    }
    return array_merge(generateSuccess(), array("data" => $result));
}

/**
 * checks if mailadress is already known
 * @param string $email mailadresse
 * @return array structured result
 */
function checkMailAddressExistentMoudleAPI($email) {
    $result = checkMailAddressExistent($email);
    return array_merge(generateSuccess(), array("data" => $result));
}

/**
 * sends username to user via mail
 * @param array $json structured request
 * @return array structured result
 */
function sendUsernameByMailApi($json){
    sendusernameByMail($json['mail']);
    return generateSuccess();
}