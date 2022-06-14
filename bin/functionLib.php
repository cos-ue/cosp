<?php
/**
 * This file contains functions, which are used all over the whole project
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * Generates Header of Page (NavBar) and some modals
 * @param bool $LOGIN need to be true if user is logged in
 * @param bool $loginpage defines if header is on login/logout page
 * @param bool $rightpages defines if it is currently on a page of impressum or privacy policy
 */
function generateHeader($LOGIN, $loginpage = false, $rightpages = false)
{
    ?>
    <nav class="navbar navbar-expand-lg sticky-top">
        <a class="navbar-brand" href="index.php">
            <img src="<?php echo config::$LOGO_BRAND ?>" style="margin-top: -5px" width="100px">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon">
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                if ($LOGIN) {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color: #d2d2d2" href="#" id="navbarDropdown"
                           role="button"
                           data-toggle="dropdown">Nutzermanagement</a>
                        <div class="dropdown-menu navbar-dropdown-menu">
                            <?php

                            if ($_SESSION['role'] >= config::$ROLE_EMPLOYEE) {
                                ?>
                                <a class="dropdown-item" href="usermgmt.php">Nutzerverwaltung</a>
                                <a class="dropdown-item" href="registration.php">Nutzer anlegen</a>
                                <?php
                            }
                            if (checkModulModuleRights(config::$ROLE_EMPLOYEE, true))
                            {
                                ?>
                                <a class="dropdown-item" href="moduleRights.php">Modulrechteverwaltung</a>
                                <?php
                            }
                            ?>
                            <a class="dropdown-item" href="myUser.php">Eigene Nutzerdaten</a>
                            <a class="dropdown-item"
                               href="validations.php"><?php echo $_SESSION['role'] >= config::$ROLE_EMPLOYEE ? "Punkteübersicht" : "Eigene Punkteübersicht" ?></a>
                        </div>
                    </li>
                    <?php
                    if ($_SESSION['role'] >= config::$ROLE_ADMIN) {
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" style="color: #d2d2d2" href="#" id="navbarDropdown"
                               role="button"
                               data-toggle="dropdown">Rollen- und Rängemanagement</a>
                            <div class="dropdown-menu navbar-dropdown-menu">
                                <a class="dropdown-item" href="roleMgmt.php">Rollenverwaltung</a>
                                <a class="dropdown-item" href="rankMgmt.php">Rangverwaltung</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" style="color: #d2d2d2" href="#" id="navbarDropdown"
                               role="button"
                               data-toggle="dropdown">API-Management</a>
                            <div class="dropdown-menu navbar-dropdown-menu">
                                <a class="dropdown-item" href="apimgmt.php">API-Verwaltung</a>
                            </div>
                        </li>
                        <?php
                    }
                    if ($_SESSION['role'] >= config::$ROLE_EMPLOYEE) {
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" style="color: #d2d2d2" href="#" id="navbarDropdown"
                               role="button"
                               data-toggle="dropdown">Admintools</a>
                            <div class="dropdown-menu navbar-dropdown-menu">
                                <a class="dropdown-item" href="statistics.php">Statistiken</a>
                            </div>
                        </li>
                        <?php
                    }
                }
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" style="color: #d2d2d2" href="#" id="navbarDropdown"
                       role="button"
                       data-toggle="dropdown">Kontakt</a>
                    <div class="dropdown-menu navbar-dropdown-menu">
                        <?php
                        if ($LOGIN) {
                            ?>
                            <a class="dropdown-item" href="contact.php">Kontakt</a>
                            <?php
                        }
                        ?>
                        <a class="dropdown-item" href="impressum.php">Impressum</a>
                        <a class="dropdown-item" href="privacy-policy.php">Datenschutz</a>
                    </div>
                </li>
                <?php if (!$loginpage && (!$rightpages || $LOGIN)) { ?>
                    <li class="nav-item">
                        <a class="nav-link disabled" style="color: #d2d2d2" href="#" tabindex="-1">Hallo <?php
                            if ($LOGIN) {
                                $b_username = true;
                                if (strlen($_SESSION['firstname']) > 0) {
                                    $b_username = false;
                                    echo $_SESSION['firstname'];
                                }
                                if (strlen($_SESSION['firstname']) > 0 && strlen($_SESSION['lastname']) > 0) {
                                    echo " ";
                                }
                                if (strlen($_SESSION['lastname']) > 0) {
                                    $b_username = false;
                                    echo $_SESSION['lastname'];
                                }
                                if ($b_username) {
                                    echo $_SESSION['name'];
                                }
                            } else {
                                echo "Gast";
                            }
                            ?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php
        if ($LOGIN) {
            ?>
            <a class="btn btn-secondary"
               href="index.php?<?php echo http_build_query(array('logout' => true), '', '&amp;'); ?>"
               style="margin-left:15px;">Abmelden
            </a>
            <?php
        } else {
            ?>
            <a class="btn btn-secondary"
               href="index.php"
               style="margin-left:15px;">Anmelden
            </a>
            <?php
        }
        ?>
    </nav>
    <input type="text" value="<?php echo createCSRFtokenClient()?>" id="TokenScriptCSRF" hidden>
    <?php
}

/**
 * dumpes data, if debug mode is enabled and filters debug output based on config::$DEBUG_LEVEL
 * @param mixed $data data that should be printed for debug purposes
 * @param int $level If the value is greater or equal than config::$DEBUG_LEVEL the variable to debug will be printed out
 * @param bool $dark if true dump has dark background
 */
function dump($data, $level = -1, $dark = false)
{
    if ($level != -1) {
        if ($level > config::$DEBUG_LEVEL) {
            return;
        }
    }
    if (config::$DEBUG) {
        ?>
        <code style='display: block;white-space: pre-wrap;'>
            <?php
            var_dump($data);
            ?>
        </code>
        <br/>
        <br/>
        <?php
        if ($dark) {
            ?>
            <div class="bg-dark">
                <code style='display: block;white-space: pre-wrap;'>
                    <?php
                    var_dump($data);
                    ?>
                </code>
                <br/>
                <br/>
            </div>
            <?php
        }
    }
}

/**
 * returns a http-status Redirect
 * @param string $url URI where user should be redirected
 * @param bool $permanent sets if redirect has http-status-code 301 (permanent redirect) or 302 (temporary redirect) if true there will be 301 in response, default it is false
 */
function Redirect($url, $permanent = false)
{
    header('Location: ' . $url, true, $permanent ? 301 : 302);
    die();
}

/**
 * generates a hmac string from a given string with the hmac secret from config.php
 * @param string $string inout string
 * @return string finished hmac
 */
function generateStringHmac($string)
{
    if (!is_string($string)) {
        die("No String!");
    }
    $hmac = hash_hmac("sha512", $string, config::$HMAC_SECRET);
    return $hmac;
}

/**
 * generates Validatable data for example for opening uapi.php, with timestamp
 * @param string $tokenstring input string, with all needed information encoded in it
 * @param string $time set time if not set, current server time will be used
 * @return array structured result wwith time, tokenstring and seccode (hmac)
 */
function generateValidatableDataMaterial($tokenstring, $time = "")
{
    if ($time === "") {
        $time = time();
    }
    $hmac = generateStringHmac($tokenstring . $time);
    $result = array(
        "token" => $tokenstring,
        "seccode" => $hmac,
        "time" => $time
    );
    return $result;
}

/**
 * checks if validatable data is correct used from e.g. uapi.php
 * @param array $data consists of timestamp, tokenstring and seccode (hmac)
 * @return bool true if hmac fits to given data
 */
function checkValidatableMaterial($data)
{
    if (isset($data['token'], $data['seccode'], $data['token']) === false) {
        die("wrong keys in array");
    }
    $hmac = generateValidatableDataMaterial($data['token'], $data['time']);
    if ($data['time'] + (24 * 60 * 60) < time()) {
        permissionDenied("Your time has exceeded.");
    }
    if (hash_equals($hmac['seccode'], $data['seccode'])) {
        return true;
    }
    permissionDenied();
    return false;
}

/**
 * generates a random sting as token and creates validatable data with this string and the username
 * @param string $username of user for which data is generated
 * @param string $tokenstring can be set, if not string will be randomly generated by parameters found in config file
 * @return array structured validatable data
 */
function generateValidatableData($username, $tokenstring = "")
{
    if ($tokenstring == "") {
        $tokenstring = generateRandomString();
    }
    $hmac = generateStringHmac($username . $tokenstring);
    $result = array(
        "token" => $tokenstring,
        "username" => $username,
        "seccode" => $hmac
    );
    return $result;
}

/**
 * generates Validatable data for example for changing a users password on its own
 * @param string $username username to generate timed validatable data
 * @param string $tokenstring input string, with all needed information encoded in it
 * @param string $time set time if not set, current server time will be used
 * @return array structured result wwith time, tokenstring and seccode (hmac) and username
 */
function generateValidatableDataTimed($username, $tokenstring = "", $time = "")
{
    if ($time === "") {
        $time = time();
    }
    $hmac = generateStringHmac($username . $tokenstring . $time);
    $result = array(
        "token" => $tokenstring,
        "username" => $username,
        "seccode" => $hmac,
        "time" => $time
    );
    return $result;
}

/**
 * generates string from validatable data
 * @param string $username username of user which should use the link
 * @param string $targetside path to target page of link in cosp
 * @param string $tokenstring can be set, if not string will be randomly generated by parameters found in config file
 * @return string resulting link from inputs
 */
function generateValidateableLink($username, $targetside, $tokenstring = "")
{
    if ($tokenstring == "") {
        $data = generateValidatableData($username);
    } else {
        $data = generateValidatableData($username, $tokenstring);
    }
    $result = "https://" . config::$DOMAIN . "/" . $targetside . "?" . http_build_query($data);
    return $result;
}

/**
 * generate link to cosp with validatable material
 *
 * @param string $username username of user which should use the link
 * @param string $targetside path to target page of link in cosp
 * @param string $tokenstring can be set, if not string will be randomly generated by parameters found in config file
 * @return string resulting link from inputs with timestamp
 */
function generateValidateableLinkTimed($username, $targetside, $tokenstring = "")
{
    if ($tokenstring == "") {
        $data = generateValidatableDataTimed($username);
    } else {
        $data = generateValidatableDataTimed($username, $tokenstring);
    }
    $result = "https://" . config::$DOMAIN . "/" . $targetside . "?" . http_build_query($data);
    return $result;
}

/**
 * checks if link from generateValidateableLink used by user is correct and data was not changed
 * @param array $data data from link (GET-Method)
 * @return bool true if data is correct
 */
function checkValidatableLink($data)
{
    if (isset($data['token'], $data['username'], $data['seccode']) === false) {
        die("wrong keys in array");
    }
    $hmac = "";
    if (isset($data['time'])) {
        $hmac = generateValidatableDataTimed($data['username'], $data['token'], $data['time']);
    } else {
        dump("validate version", 3);
        $hmac = generateValidatableData($data['username'], $data['token']);
    }
    dump($hmac, 4);
    dump($data, 4);
    if ($hmac !== "") {
        if (hash_equals($hmac['seccode'], $data['seccode'])) {
            dump('validated', 3);
            return true;
        }
    }
    dump('not validated', 3);
    return false;
}

/**
 * generates random string
 * @param int $length can be set, if not using parameters from config file
 * @param bool $special if true special chars are added
 * @return string random string with certain length
 */
function generateRandomString($length = -1, $special = false)
{
    if ($length === -1) {
        $length = config::$RANDOM_STRING_LENGTH;
    }
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($special) {
        $characters = $characters . ",.;:-_+#/*";
    }
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * denies access to a page if called
 * @param string $string optional deny message
 */
function permissionDenied($string = "")
{
    if ($string === "") {
        echo "You shall not pass!";
    } else {
        echo $string;
    }
    http_response_code(403);
    die();
}

/**
 * gives access to a page and generates a http-code 200 ; used for policy reasons with cross site javascript from docked in applications
 * @param string $string optional access message
 */
function grantPermission($string = "")
{
    if ($string === "") {
        echo "You shall pass!";
    } else {
        echo $string;
    }
    http_response_code(200);
    die();
}

/**
 * set http-code 418 (I'm a teapot), called if parameters of api not matching
 */
function ServerError()
{
    echo "Correct your parameters, until then im acting like a teapot!";
    http_response_code(418);
    die();
}

/**
 * generates standard header-content of html-page like javascript includes used everywhere
 * @param array $additional additional stylesheets or javascript could be inserted with this
 */
function generateHeaderTags($additional = array())
{
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <?php
    //map for stylesheed to be included in header
    //key -> path + name to file;
    //value -> true: toggle with debug mode, false: outputs minified version every time
    $css_map = [
        "csse/bootstrap" => false,
        "csse/bootstrap-slider" => false,
        "csse/all" => false,
        "csse/fontawesome" => false,
        "css/main" => true,
    ];
    foreach ($css_map as $name => $use_debug) {
        echo '<link rel="stylesheet" type="text/css" href="'.$name.(!$use_debug || !config::$DEBUG? '.min' : '').'.css">';
    }

    //map for scriptfiles to be included in header
    //key -> path + name to file;
    //value -> true: toggle with debug mode, false: outputs minified version every time
    $js_map = [
        "jse/jquery-3.4.1" => false,
        "jse/popper" => false,
        "jse/bootstrap" => false,
        "jse/bootstrap-slider" => false,
        "js/coseMainLib" => true,
        "tjs/coseMainLibNg" => true,
    ];

    foreach ($js_map as $name => $use_debug) {
        echo '<script type="text/javascript" src="'.$name.(!$use_debug || !config::$DEBUG? '.min' : '').'.js"></script>';
    }

    if (sizeof($additional) > 0) {
        foreach ($additional as $line) {
            $href = $line['hrefmin']?? $line['href'];  //php 7 syntax
            if (config::$DEBUG) {
                $href = $line['href'];
            }
            switch ($line['type']) {
                case 'style':
                case 'css':
                case 'link':
                    echo '<link rel="' . $line['rel'] . '" href="' . $href . '"'. (($line['typeval']?? '')? ' type="' . $line['typeval'] . '"': '') .'>';
                    break;
                case 'js':
                case 'script':
                    echo '<script type="' . $line['typeval'] . '" src="' . $href . '" ></script>';
                    break;
            }
        }
    }
}

/**
 * checks if a given role-id is existent
 * @param int $rid id of role which existent should be checked
 * @return bool true if id existent
 */
function checkRoleID($rid)
{
    $Roles = getAllRolles();
    foreach ($Roles as $Role) {
        if ($Role['id'] == $rid) {
            return true;
        }
    }
    return false;
}

/**
 * checks if certain api token is existent
 * @param string $token token to check
 * @return bool true if api token exists
 */
function checkApiTokenExists($token)
{
    $Tokens = getAllApiTokens();
    foreach ($Tokens as $dbtoken) {
        if ($dbtoken['token'] == $token) {
            return true;
        }
    }
    return false;
}

/**
 * decodes a json from a string
 * @param string $string input json
 * @return mixed structured data from json as array
 */
function decode_json($string)
{
    return json_decode($string, true);
}

/**
 * checks if mail address seems to be valid
 * @param string $email given mail address
 * @return mixed false if mail address syntax is incorrect
 */
function checkMailAddress($email)
{
    $result = filter_var($email, FILTER_VALIDATE_EMAIL);
    dump("Email-Address-Checker:" . $email, 8);
    dump($result, 8);
    return $result;
}

/**
 * checks if a given reason exists
 * @param string $reason given reason
 * @return false|int false if not existent
 */
function checkReasonExists($reason)
{
    $reasons = getAllReasons();
    if (in_array($reason, $reasons)) {
        return array_search($reason, $reasons);
    }
    return false;
}

/**
 * creates base64 encoded thumbnails with width of 300pc
 * @param string $picture path to picture
 * @return string base64 encoded preview picture
 */
function createThumbnail($picture)
{
    list($width, $height) = getimagesize($picture);

    // if the picture ist wider than 300 px, calculate size of preview picture while keeping the original aspect ratio
    if ($width > 300) {
        $ratio = $height / $width;
        $newwidth = 300;
        $newheight = 300 * $ratio;
    } else {
        $newwidth = $width;
        $newheight = $height;
    }

    // load picture from file
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    if (pathinfo($picture)['extension'] === 'png' || pathinfo($picture)['extension'] === 'PNG') {
        $source = imagecreatefrompng($picture);
    } else if (pathinfo($picture)['extension'] === 'gif' || pathinfo($picture)['extension'] === 'GIF') {
        $source = imagecreatefromgif($picture);
    } else {
        $source = imagecreatefromjpeg($picture);
    }

    //prevent Black background
    if (pathinfo($picture)['extension'] == "gif" || pathinfo($picture)['extension'] == "png" || pathinfo($picture)['extension'] == "GIF" || pathinfo($picture)['extension'] == "PNG") {
        imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
    }

    // scale and return result as base64
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    ob_start();
    imagepng($thumb);
    $buffer = ob_get_clean();
    ob_end_clean();
    $result = base64_encode($buffer);
    return "data:image/png;base64," . $result;
}

/**
 * checks if user has the required permission
 * @param int $requiredPermission permission value needed to access page
 */
function checkPermission($requiredPermission)
{
    dump($_SESSION, 5);
    dump($requiredPermission, 5);
    if ($_SESSION['role'] < $requiredPermission) {
        permissionDenied("#39: There is no such thing as a coincidence.");
    }
}

/**
 * deletes all references for a certain Picture
 * @param string $picToken unique Picture Identifier
 * @param boolean $overwrite overwrites default behaviour and deletes data direct
 */
function deletePictureReferences($picToken, $overwrite)
{
    $rapis = getAllApiTokens();
    if (config::$DIRECT_DELETE || $overwrite) {
        foreach ($rapis as $rapi) {
            ApiCall(array('picToken' => $picToken, 'override' => true), 'rpt', $rapi['token'], $rapi['apiuri'], false);
        }
    } else {
        foreach ($rapis as $rapi) {
            ApiCall(array('picToken' => $picToken), 'rpt', $rapi['token'], $rapi['apiuri'], false);
        }
    }
}

/**
 * restores all references for a certain Picture
 * @param string $picToken unique Picture Identifier
 */
function restorePictureReferences($picToken)
{
    $rapis = getAllApiTokens();
    foreach ($rapis as $rapi) {
        ApiCall(array('picToken' => $picToken), 'rpc', $rapi['token'], $rapi['apiuri'], false);
    }
}

/**
 * call other apis, used for reverse-apis of application
 * @param array $params data to encode
 * @param string $type type of request in string format
 * @param string $token auth token
 * @param string $url url of called api
 * @param bool $file_upload set to true if u want to upload a file
 * @return mixed|array result of request, if successful it will be an array
 */
function ApiCall($params, $type, $token, $url, $file_upload = false)
{
    $postData = array(
        "token" => $token,
        "type" => $type
    );
    $postData = array_merge($postData, $params);
    $ch = curl_init('https://' . $url);
    $standard_ar = array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
    );
    $specialAR = array(
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    );
    if ($file_upload) {
        $specialAR = array(
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: multipart/form-data'
            ),
        );
    }
    foreach (array_keys($specialAR) as $key) {
        $standard_ar[$key] = $specialAR[$key];
    }
    dump($standard_ar, 9);
    curl_setopt_array($ch, $standard_ar);

    // Send the request
    $response = curl_exec($ch);
    dump($response, 3);

    if ($response === FALSE) {
        return (array('code' => 1));
    }
    // Decode the response
    $responseData = decode_json($response);
    dump($responseData, 9);
    if (is_array($responseData)) {
        if (key_exists("code", $responseData)) {
            if ($responseData["code"] === 0) {
                return $responseData;
            }
        }
    }
    return (array('code' => 1));
}

/**
 * activates or deactivates an user account on another application connected to cosp
 * @param string $uri url to reverse-api
 * @param string $token token for reverse-api
 * @param string $username username of user which is going to be en-/disabled
 * @param bool $AktivationSate if true user will be enabled
 * @return array|mixed result of request, if successful it will be an array
 */
function AktivationRemoteUser($uri, $token, $username, $AktivationSate)
{
    if ($AktivationSate) {
        return ApiCall(array('username' => $username), 'rau', $token, $uri);
    } else {
        return ApiCall(array('username' => $username), 'dau', $token, $uri);
    }
}

/**
 * hashes a string with sha512
 * @param string $string given string
 * @return string sha512 value of string
 */
function hashString($string)
{
    return hash('sha512', $string);
}

/**
 * sends passwort reset mail to user
 * @param string $username username of user which requested to change his password
 * @param string $email mail to send reset password to
 */
function PasswordResetViaMail($username, $email)
{
    $completeLink = generateValidateableLinkTimed($username, "resetPwd.php");
    dump($email, 3);
    sendMail($email, "Neusetzen des Passwortes für " . $username, MailTemplates::ResetPasswordByMail($completeLink, $username), true);
}

/**
 * checks if a given rank-id is existent
 * @param int $rid id of rank which existent should be checked
 * @return bool true if id existent
 */
function checkRankID($rid)
{
    $Ranks = getRanks();
    foreach ($Ranks as $Rank) {
        if ($Rank['id'] == $rid) {
            return true;
        }
    }
    return false;
}

/**
 * deletes story references to a certain story in each module of plattform
 * @param string $storyToken identifier of story
 * @param bool $overwrite sets if story is going to be deleted finally
 */
function deleteStoryReference($storyToken, $overwrite = false)
{
    $rapis = getAllApiTokens();
    foreach ($rapis as $rapi) {
        ApiCall(array('StoryToken' => $storyToken, "overwrite" => $overwrite), 'dus', $rapi['token'], $rapi['apiuri']);
    }
}

/**
 * restores story references to a certain story in each module of plattform
 * @param string $storyToken identifier of story
 */
function restoreStoryReference($storyToken)
{
    $rapis = getAllApiTokens();
    foreach ($rapis as $rapi) {
        ApiCall(array('StoryToken' => $storyToken), 'rst', $rapi['token'], $rapi['apiuri']);
    }
}

/**
 * checks if a user is listed as staff
 * @param string $name username of user, which should be checked
 * @return bool True if user is Staff
 */
function isStaff($name)
{
    $user = getUserData($name);
    $roleval = getRoleByID($user['role'])['value'];
    if ($roleval >= config::$ROLE_EMPLOYEE) {
        return true;
    }
    return false;
}

/**
 * check if user has right on at least one module
 * @param int $requiredPermission Value of needed right
 * @param boolean $return set if value should be returned, standard is false
 * @return boolean|void true if user has asked right and $return is set
 */
function checkModulModuleRights($requiredPermission, $return = false) {
    $current_user_id = getUserData($_SESSION['name'])['id'];
    $current_user_permissions = getAllModuleBasedRightsByUserID($current_user_id);
    dump($current_user_permissions,2);
    $allowed = false;
    if ($_SESSION['role'] >= $requiredPermission) {
        $allowed = true;
    }
    if ($allowed == false && count($current_user_permissions) > 0) {
        dump("loop", 2);
        foreach ($current_user_permissions as $permission) {
            if ($permission['value'] >= $requiredPermission) {
                $allowed = true;
            }
        }
    }
    if ($return) {
        return $allowed;
    }
    if ($allowed == false) {
        permissionDenied("#39: There is no such thing as a coincidence.");
    }
}

/**
 * counts the modules on which the given or current user has at least employee rights
 * @param string $user username of another user;
 */
function countRightsOnModules($user = "") {
    $current_user_id = getUserData($_SESSION['name'])['id'];
    if ($user !== "") {
        $current_user_id = getUserData($user)['id'];
    }
    $current_user_permissions = getAllModuleBasedRightsByUserID($current_user_id);
    $counter = 0;
    foreach ($current_user_permissions as $permission) {
        if ($permission['value'] >= config::$ROLE_EMPLOYEE) {
            $counter ++;
        }
    }
    return $counter;
}

/**
 * get ip adress of user
 * @return string ip adress of user
 */
function getUserIp(){
    $ip = "0.0.0.0";
    if (isset($_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * checks if mailadress is already in database
 * @param string $email emailadress to check
 * @param bool $debug_enable enables all times false in debug mode
 * @return bool true if email is already known
 */
function checkMailAddressExistent($email, $debug_enable = false) {
    $users = getAllUsers();
    $mails = array();
    foreach ($users as $user) {
        $mails[] = $user['email'];
    }
    if (config::$DEBUG && $debug_enable == true){
        return false;
    }
    return in_array($email, $mails);
}

/**
 * sends user his name via his mailadress
 * @param string $mailadress mailadress of the user
 */
function sendusernameByMail($mailadress) {
    $userdata = getUserDataByMailadress($mailadress);
    $username = $userdata['name'];
    dump($username, 2);
    sendMail($userdata['email'], "Ihr Nutzername auf " . config::$MAIN_CAPTION, MailTemplates::SendUsernameByMail($userdata['name']), true, config::$SENDER_ADDRESS);
}