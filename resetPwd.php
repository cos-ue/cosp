<?php
/**
 * Page to reset users password without login via link in email
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
$CheckValid = false;
$pwdsEqual = true;
$pwdsCorrectLength = true;
if (config::$MAINTENANCE) {
    Redirect("/index.php");
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <?php
    $GetData = array();
    if (count($_GET) > 0) {
        if (isset($_GET['token'], $_GET['username'], $_GET['seccode'])) {
            dump($_GET, 1);
            $GetData = array(
                "token" => filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING),
                "username" => filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING),
                "seccode" => filter_input(INPUT_GET, 'seccode', FILTER_SANITIZE_STRING),
                "time" => filter_input(INPUT_GET, 'time', FILTER_SANITIZE_STRING)
            );
            dump($GetData, 3);
            $CheckValid = checkValidatableLink($GetData);
            if ($CheckValid) {
                $CheckValid = in_array($GetData['username'], getAllUsernames());
            }
        }
    }
    else {
        die("You're not allowed to pass!");
    }
    $updated = false;
    if ($CheckValid) {
        $getData2 = array();
        if (count($_POST) > 0) {
            if (isset($_POST['password1'], $_POST['password2'])) {
                dump($_GET, 1);
                $GetData2 = array(
                    "password1" => filter_input(INPUT_POST, 'password1'),
                    "password2" => filter_input(INPUT_POST, 'password2'),
                    "username" => $GetData['username']
                );
                $csrf = filter_input(INPUT_POST, 'FormTokenScriptCSRF', FILTER_SANITIZE_STRING);
                if (!isset($_SESSION['csrf'])) {
                    Redirect('index.php', false);
                }
                if (!checkCSRFtoken($csrf)) {
                    Redirect('index.php', false);
                }
                dump($GetData2, 3);
                $userData = getUserData($GetData2['username']);
                dump($userData, 3);
                if (in_array($userData['id'], getAllUserIds())) {
                    $pwdsEqual = $GetData2['password1'] == $GetData2['password2'];
                    $pwdsCorrectLength = strlen($GetData2['password1']) >= config::$PWD_LENGTH;
                    dump($pwdsEqual, 3);
                    dump($pwdsCorrectLength, 3);
                    if ($pwdsEqual && $pwdsCorrectLength) {
                        updateUserPassword($userData['id'], $GetData2['password1']);
                        $updated = true;
                    }
                }
            }
        }
    }
    ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>
        <?php
        echo config::$MAIN_CAPTION . " - " . config::$TAGLINE_CAPTION;
        ?>
    </title>
    <?php
    generateHeaderTags();
    ?>
</head>

<body>
<?php
generateHeader(false);
?>
<div class="d-flex align-items-center justify-content-center h-100" style="margin-top: -58px">
    <div class="d-flex flex-column col-4">
        <h1 class="weiß2 mb-4">Passwort zurücksetzen</h1>
        <?php
        if ($CheckValid) {
            if ($updated === false) {
                $actual_link = "https://" . config::$DOMAIN . "$_SERVER[REQUEST_URI]";
                ?>
                <form action="<?php echo $actual_link; ?>" method="post" name="wishes">
                    <input type="text" value="<?php echo createCSRFtokenClient()?>" id="FormTokenScriptCSRF" name="FormTokenScriptCSRF" hidden>
                    <label class="weiß2">neues Passwort (mindestends <?php echo config::$PWD_LENGTH ?> Zeichen)</label>
                    <input class="form-control textinput
                    <?php
                    $html = "";
                    if (!($pwdsEqual && $pwdsCorrectLength)) {
                        $html .= "border-danger bg-danger" . '" ' . 'data-toggle="tooltip" title="';
                        if (!$pwdsEqual) {
                            $html .= 'Die Felder enthalten nicht das gleiche Passwort. ';
                        }
                        if (!$pwdsCorrectLength) {
                            $html .= 'Das Passwort muss mindestens ' . config::$PWD_LENGTH . ' Zeichen lang sein.';
                        }
                    }
                    echo $html;
                    ?>
                    " type="password" name="password1" required><br>

                    <label class="weiß2">neues Passwort wiederholen</label>
                    <input class="form-control textinput
                    <?php
                    echo $html;
                    ?>
                    " type="password" name="password2" required><br>
                    <input class="btn btn-success btn-important col-4" name="submitrequest" type="submit"
                           value="Ändern" style="float: right">
                </form>
                <?php
            } else {
                ?>
                <h2 style="color: #ffffff">Passwort erfolgreich geändert.</h2>
                <?php
            }
        } else {
            ?>
            <h2 style="color: #ffffff">Sie haben einen falschen Link.</h2>
            <?php
        }
        ?>
    </div>
</div>
</body>
</html>
