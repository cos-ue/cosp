<?php
/**
 * API-Endpoint for module communication
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] != 'POST' && $_SERVER['REQUEST_METHOD'] != 'PUT') {
    permissionDenied();
}
if (key_exists("CONTENT_TYPE", $_SERVER) === false) {
    permissionDenied();
}
if ($_SERVER["CONTENT_TYPE"] !== "application/json" && strpos("multipart/form-data", $_SERVER["CONTENT_TYPE"])) { //multipart/form-data
    permissionDenied();
}
$input = file_get_contents('php://input');
$json = decode_json($input);
if ($json === null) {
    $json = array();
    foreach (array_keys($_POST) as $key) {
        $json[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
    }
}
if (key_exists('token', $json) == false) {
    permissionDenied();
}
if (checkApiTokenExists($json['token']) === false) {
    permissionDenied();
}
switch ($json['type']) {
    case 'rud':  //request User Data
        if (key_exists('username', $json)) {
            generateJson(getUserdataApi($json['username'], $json['ignore'], $json['token']));
        }
        break;
    case 'rrd': //request role data
        if (key_exists('username', $json)) {
            generateJson(getRoledataApi($json['username']));
        }
        break;
    case "arp": //add rank points
        if (key_exists('username', $json) == false) {
            generateJson(generateError());
        }
        if (key_exists('points', $json) == false) {
            generateJson(generateError());
        }
        if (key_exists('reason', $json) == false) {
            generateJson(generateError());
        }
        $result = addRankPoints($json['username'], $json['token'], $json['reason'], $json['points']);
        generateJson($result);
        break;
    case "grp": // get rank points for user
        if (key_exists('username', $json) == false) {
            generateJson(generateError());
        }
        $result = getRankPoints($json['username'], $json['token']);
        generateJson($result);
        break;
    case "gjp": //get rank points for last year and user
        if (key_exists('username', $json) == false) {
            generateJson(generateError());
        }
        $result = getRankPointsLastYear($json['username'], $json['token']);
        generateJson($result);
        break;
    case "grl": // get rank list
        $result = getRankLists($json['token']);
        generateJson($result);
        break;
    case "gau": // get all usernames
        $result = getAllUsernamesApi();
        generateJson($result);
        break;
    case "aud": // add user data
        $result = addUserdataRemote($json);
        generateJson($result);
        break;
    case "pul": //picture upload
        $result = UploadPicture($json);
        generateJson($result);
        break;
    case "gsc": // get seccode
        $result = getSeccode($json['pictureToken'], $json['token'], $json['username'], $json['rank-val']);
        generateJson($result);
        break;
    case "gpl": // get picture List
        $result = getPictureList($json['token'], $json['username'], $json['rank-val']);
        generateJson($result);
        break;
    case "grt": //get rank types
        $result = getRankTypes($json['token']);
        generateJson($result);
        break;
    case 'aus': //add user storie
        $result = uploadStory($json);
        generateJson($result);
        break;
    case 'gus': //get user Stories;
        $result = getUserStories($json);
        generateJson($result);
        break;
    case 'gas': // get data to load all at once
        $result = getAllUserStories($json);
        generateJson($result);
        break;
    case 'gsm': //getSingleMaterial
        $result = getSinglePicture($json['token'], $json['username'], $json['rank-val'], $json['pictureToken']);
        generateJson($result);
        break;
    case 'ssm': //save single Material edit
        $result = saveSinglePictureMaterial($json);
        generateJson($result);
        break;
    case 'eus': //edit user story save
        $result = EditStoryAPI($json['storytoken'], $json['title'], $json['story']);
        generateJson($result);
        break;
    case 'rup': // reset user password
        $result = resetUserPasswordViaAPI($json);
        generateJson($result);
        break;
    case 'dsp': // delete single picture
        $result = deletePictureApi($json);
        generateJson($result);
        break;
    case 'gsl': //get stories as list
        $result = getStoriesAsListByListApi($json);
        generateJson($result);
        break;
    case 'dus': //delete User Story
        $result = deleteUserStoryApi($json);
        generateJson($result);
        break;
    case 'gst': //get story title of all stories
        $result = getStoryTitleAsListByListApi($json);
        generateJson($result);
        break;
    case 'asa': // approve story
        $result = approveUserStorie($json);
        generateJson($result);
        break;
    case 'das': // disapprove story
        $result = disapproveUserStorie($json);
        generateJson($result);
        break;
    case 'scm': //send contact mail
        $result = sendContactMail($json);
        generateJson($result);
        break;
    case 'gca': // get captcha code
        $result = getCaptchaAPI($json);
        generateJson($result);
        break;
    case 'rst': //restore story
        $result = RestoreStoryAPI($json);
        generateJson(($result));
        break;
    case 'fst': //final delete story
        $result = FinalDeleteStory($json);
        generateJson(($result));
        break;
    case 'rpc': //restore picture
        $result = RestorePictureAPI($json);
        generateJson(($result));
        break;
    case 'fpc': //final delete picture
        $result = FinalDeletePicture($json);
        generateJson(($result));
        break;
    case 'gts': // get source types
        $result = getSourceTypesAPI();
        generateJson($result);
        break;
    case 'cma': //check mailadress exists
        $result = checkMailAddressExistentMoudleAPI($json['mail']);
        generateJson($result);
        break;
    case 'sua': //sendusername by Mail
        $result = sendUsernameByMailApi($json);
        generateJson($result);
        break;
    default: // on default you will always get an error
        generateJson(generateError());
        break;
}