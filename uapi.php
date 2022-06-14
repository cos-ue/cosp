<?php
/**
 * Endpoint of users api
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
$uris = getAllApiTokens();
$base_urls = array();
foreach ($uris as $uri) {
    if (ctype_space($uri['apiuri']) == false && $uri['apiuri'] != "" && $uri['apiuri'] != null) {
        $baseUrl = 'https://' . parse_url('https://' . $uri['apiuri'])['host'];
        $base_urls[] = $baseUrl;
    }
}
if (key_exists('HTTP_ORIGIN', $_SERVER)) {
    if (in_array($_SERVER['HTTP_ORIGIN'], $base_urls)) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Max-Age: 1728000');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("Content-Length: 0");
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] != 'POST' && $_SERVER['REQUEST_METHOD'] != 'GET' && $_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
    permissionDenied();
} else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    grantPermission();
}
$json = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    foreach (array_keys($_GET) as $key) {
        $json[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $json = decode_json($input);
    if (count($json) < 1 ) {
        foreach (array_keys($_POST) as $key) {
            $json[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
        }
    }
}
if (checkValidatableMaterial(array("token" => $json['data'], "seccode" => $json['seccode'], "time" => $json['time']))) {
    switch ($json['type']) {
        case 'gpp': //get picture preview
            $token = explode(";", $json['data']);
            header('Content-Type: image/png');
            $imagedata = getPreviewPictureAPI($token[0]);
            $imagedata = substr($imagedata, strpos($imagedata, ',') + 1);
            echo base64_decode($imagedata);
            echo 'test';
            break;
        case 'gpf': //get picture fullsize
            $token = explode(";", $json['data']);
            getPictureFullsizeAPI($token[0]);
            break;
        case 'gus': //get Story Data by One Link per Story
            $result = getStoryDataUAPI($json['data']);
            generateJsonUAPI($result);
            break;
        case 'gas': // get All Stories at once
            $result = getStoriesDataUAPI($json['data']);
            generateJsonUAPI($result);
            break;
        case 'vas': // validate Story
            $result = validateStory($json['data']);
            generateJsonUAPI($result);
            break;
        case 'vap': // validate Picture
            $result = validatePicture($json['data']);
            generateJsonUAPI($result);
            break;
    }
}