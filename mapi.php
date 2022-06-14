<?php
/**
 * Management api endpoint
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    permissionDenied("Not Post");
}
if (key_exists("CONTENT_TYPE", $_SERVER) === false) {
    permissionDenied("Content type unset");
}
if ($_SERVER["CONTENT_TYPE"] !== "application/json") {
    permissionDenied("Content type");
}
$json = array();
if (count($_POST) > 0) {
    foreach (array_keys($_POST) as $key) {
        $json[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
    }
} else if (count($_GET) > 0) {
    foreach (array_keys($_GET) as $key) {
        $json[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
    }
} else {
    $input = file_get_contents('php://input');
    $json = decode_json($input);
}
if (isset($_SESSION["name"]) == false) {
    if ($json['type'] != 'cma' && $json['type'] != 'cue' && $json['type'] != 'ibd' && $json['type'] != 'cpa') {
        permissionDenied("Username");
    }
}
if (!isset($_SESSION['csrf'])) {
    permissionDenied("CSRF");
}
if (!checkCSRFtoken($json['csrf'])) {
    permissionDenied("CSRF wrong");
}
switch ($json['type']) {
    case 'cur': //change user role
        $result = changeRole($json);
        generateJson($result);
        break;
    case 'anr': //add new role
        $result = addNewRole($json);
        generateJson($result);
        break;
    case 'eer': //edit existing roles
        $result = saveEditRoleMapi($json);
        generateJson($result);
        break;
    case 'der': //delete role
        $result = deleteRoleMapi($json['id']);
        generateJson($result);
        break;
    case 'cup': //change user password
        $result = changeUserPasswordMapi($json);
        generateJson($result);
        break;
    case 'teu': //toggle enable/disable user
        $result = enableUserMAPI($json['id']);
        generateJson($result);
        break;
    case 'rup': //reset user password
        $result = resetUserPasswordMAPI($json['id']);
        generateJson($result);
        break;
    case 'adr': //add new rank
        $result = addNewRank($json);
        generateJson($result);
        break;
    case 'dra': //delete rank
        $result = deleteRankMapi($json['id']);
        generateJson($result);
        break;
    case 'era': //edit existing rank
        $result = saveEditRankMapi($json);
        generateJson($result);
        break;
    case 'gsd': //get statistics Data
        $result = getStatisticalDataAPI($json);
        generateJson($result);
        break;
    case 'gar': //get all role names
        $result = getAllRoleNamesAPI();
        generateJson($result);
        break;
    case 'grn': //get rank names
        $result = getAllRankNamesAPI();
        generateJson($result);
        break;
    case 'cpa': //get captcha
        $result = generateCaptchaAPI();
        generateJson($result);
        break;
    case 'cmg': //send contact message
        $result = sendContactMessage($json);
        generateJson($result);
        break;
    case 'rns': // data for api-select for module based rank names
        $result = getUnusedApisForRank($json);
        generateJson($result);
        break;
    case 'rna': //all module based names of a rank
        $result = getModuleRankNames($json);
        generateJson($result);
        break;
    case 'imr': //insert module based rank name
        $result = insertModuleBasedRankNameMapi($json);
        generateJson($result);
        break;
    case 'dmr': //delete module based rank name
        $result = delteModuleBasedRankNameMapi($json);
        generateJson($result);
        break;
    case 'gap': //get api data
        $result = getAllApiData($json);
        generateJson($result);
        break;
    case 'sap': // save api data
        $result = saveApiDataAPI($json);
        generateJson($result);
        break;
    case 'gmr': //get module rights
        $result = getModulBasedRights($json);
        generateJson($result);
        break;
    case 'gam': //getAllModules
        $result = getAllModules();
        generateJson($result);
        break;
    case 'sar': //selected role data
        $result = getSelectedRoles();
        generateJson($result);
        break;
    case 'smr': //save module role
        $result = addModuleRoleMapi($json);
        generateJson($result);
        break;
    case 'drm': //delete module based user role
        $result = deleteModuleBasedRoleMapi($json);
        generateJson($result);
        break;
    case 'smv': //set mailvalidation value
        $result = setMailValidationValue($json);
        generateJson($result);
        break;
    case 'umr': //update Module rights
        $result = updateModuleRightsMapi($json);
        generateJson($result);
        break;
    case 'dsr': //set disable right state
        $result = setDisableModuleRightStateMapi($json);
        generateJson($result);
        break;
    case 'gum': //get user without rights for module
        $result = getUnsubscribedUserForModule($json);
        generateJson($result);
        break;
    case 'cna': //create new api or module
        $result = addNewModuleApi($json);
        generateJson($result);
        break;
    case 'cma': //check mail address
        $result = checkMailAddressExistentAPI($json);
        generateJson($result);
        break;
    case 'ibd': //insert browserdata into datastore
        $result = insertBrowserDataAPI();
        generateJson($result);
        break;
    case 'cue': //check username exists
        $result = checkMailUsernameExistentAPI($json);
        generateJson($result);
        break;
    default:
        $result = generateFalse("wrong request parameters.");
        generateJson($result);
        break;
}