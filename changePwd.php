<?php
/**
 * Page to change users password
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
$CheckValid = false;
checkLoginDeny($LOGIN);
checkPermission(config::$ROLE_AUTH_USER);
$failurePWD = false;
$failurePWDOld = false;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <?php
    $updated = false;
    $getData2 = array();
    if (count($_POST) > 0) {
        if (isset($_POST['password1'], $_POST['password2'], $_POST['passwordOld'])) {
            dump($_GET, 1);
            $GetData2 = array(
                "password1" => filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING),
                "password2" => filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING),
                "passwordOld" => filter_input(INPUT_POST, 'passwordOld', FILTER_SANITIZE_STRING),
                "username" => $_SESSION['name'],
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
            if (in_array($userData['id'], getAllUserIds()) && $GetData2['password1'] !== $GetData2['passwordOld']) {
                $check = inspectPassword($GetData2['password1'], $GetData2['password2']);
                dump($check, 3);
                $checkOld = checkPasswordOnly($GetData2['username'], $GetData2['passwordOld']);
                if ($check && $checkOld) {
                    updateUserPassword($userData['id'], $GetData2['password1']);
                    $updated = true;
                } else {
                    if (!$check) {
                        $failurePWD = true;
                    }
                    if (!$checkOld) {
                        $failurePWDOld = true;
                    }
                }
            } else {
                $failurePWD = true;
                $failurePWDOld = true;
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
generateHeader(true);
?>
<div class="container mx-auto pt-3">
    <div class="justify-content-center">
        <h1 class="weiß2">Passwort Neusetzen</h1>
        <?php
        if ($updated === false) {
            $actual_link = "https://" . config::$DOMAIN . "$_SERVER[REQUEST_URI]";
            ?>
            <form action="<?php echo $actual_link; ?>" method="post" name="wishes" class="col-6">
                <input type="text" value="<?php echo createCSRFtokenClient()?>" id="FormTokenScriptCSRF" name="FormTokenScriptCSRF" hidden>
                <label class="weiß2">Nutzername*</label>
                <input class="form-control textinput" type="text" name="username" id="username_reg"
                    <?php
                    echo 'value="' . $_SESSION['name'] . '" ';
                    ?>
                       disabled><br>
                <label class="weiß2">Altes Passwort</label>
                <input class="form-control textinput
                    <?php
                if ($failurePWDOld) {
                    echo "border-danger bg-danger" . '" ' . 'data-toggle="tooltip" title="Ihr Passwort ist falsch."';
                }
                ?>
                    " type="password" name="passwordOld" required><br>
                <label class="weiß2">Passwort*</label>
                <input class="form-control textinput
                    <?php
                if ($failurePWD) {
                    echo "border-danger bg-danger" . '" ' . 'data-toggle="tooltip" title="Bitte ein Passwort mit mindestens ' . config::$PWD_LENGTH . " Zeichen verwenden und in beide Felder identische Passwörter eingeben.";
                }
                ?>
                    " type="password" name="password1" required><br>
                <label class="weiß2">Passwort*</label>
                <input class="form-control textinput
                    <?php
                if ($failurePWD) {
                    echo "border-danger bg-danger" . '" ' . 'data-toggle="tooltip" title="Bitte ein Passwort mit mindestens ' . config::$PWD_LENGTH . " Zeichen verwenden und in beide Felder identische Passwörter eingeben.";
                }
                ?>
                    " type="password" name="password2" required><br>
                <div>
                    <input class="btn btn-success btn-lg btn-block" name="submitrequest" type="submit"
                           value="Ändern">
                </div>
            </form>
            <?php
        } else {
            ?>
            <h2 style="color: #ffffff">Passwort erfolgreich geändert.</h2>
            <?php
        }
        ?>
    </div>
</div>
</body>
</html>
