<?php
/**
 * Page to reset reset user-password or send username via mail
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
$n = -1;
$e = -1;
$verhalten = 0;
if (count($_GET) > 0) {
    $n = filter_input(INPUT_GET, "n", FILTER_SANITIZE_STRING);
    $e = filter_input(INPUT_GET, 'e', FILTER_SANITIZE_STRING);
    if ($n == 1 && $e == 0) {
        $verhalten = 1;
    } else if ($n == 0 && $e == 1) {
        $verhalten = 2;
    } else {
        Redirect('index.php', false);
    }
} else {
    Redirect('index.php', false);
}
$formlink = "forgottenLogin.php?" . http_build_query(array('n' => $n, "e" => $e));
if (count($_POST) > 0) {
    if ($n == 1 && $e == 0) {
        dump('username', 3);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        dump($username, 3);
        $usernames = getAllUsernames();
        if (in_array($username, $usernames)) {
            $userdata = getUserData($username);
            PasswordResetViaMail($username, $userdata['email']);
            $verhalten = 3;
        }
    } else if ($n == 0 && $e == 1) {
        $mailadress = filter_input(INPUT_POST, 'mailadress', FILTER_SANITIZE_STRING);
        dump($mailadress, 3);
        if (checkMailAddressExistent($mailadress, false)) {
            sendusernameByMail($mailadress);
            $verhalten = 4;
        }
    }
}
dump($verhalten,2);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>
        <?php
        echo config::$MAIN_CAPTION . " - " . config::$TAGLINE_CAPTION;
        ?>
    </title>
    <?php
    generateHeaderTags(
        array(
            array(
                "type" => "script",
                "typeval" => "text/javascript",
                "href" => "tjs/forgottenLogin.js",
                "hrefmin" => "tjs/forgottenLogin.min.js"
            )
        )
    );
    ?>
</head>

<body>
<?php
generateHeader(false);
if ($verhalten == 1) {
    ?>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="card card-login col-6">
            <div class="card-body d-flex align-items-center flex-column">
                <h3 style="color: white">Ich kenne meinen Nutzernamen</h3>
                <form id="forgottenLoginUsernameForm" method="post" class="full-width" action="<?php echo $formlink ?>">
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 45px">
                                        <img src="images/user.svg" height="25px">
                                    </span>
                        </div>
                        <input type="text" name="username" class="form-control textinput"
                               placeholder="Nutzername" id="usernameForgottenLogin">
                    </div>
                    <div class="input-group form-group">
                        <a name="Einloggen" class="btn btn-important full-width"
                           onclick="forgottenLoginPasswordReset()">Passwort zurücksetzen
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
} else if ($verhalten == 2) {
    ?>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="card card-login col-6">
            <div class="card-body d-flex align-items-center flex-column">
                <h3 style="color: white">Ich kenne meine E-Mailadresse</h3>
                <form id="forgottenLoginMailadressForm" method="post" class="full-width"
                      action="<?php echo $formlink ?>">
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <img src="images/envelope-solid.svg" height="25px">
                                    </span>
                        </div>
                        <input type="text" name="mailadress" class="form-control textinput"
                               placeholder="E-Mailadresse" id="usernameForgottenMailAdresse">

                    </div>
                    <div class="input-group form-group">
                        <a name="Einloggen" class="btn btn-important full-width"
                           value="Nutzername Anfordern" onclick="forgottenLoginUsernameReset();">Nutzername
                            Anfordern
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
} else if ($verhalten == 3) {
    ?>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card card-login col-5 align-items-center">
            <h3 class="mt-3 mb-3" style="color: white">Passwort vergessen?</h3>
            <div class="text-light mb-3" style="text-align: center">
                Ihnen wurde eine E-Mail zum Ändern des Passworts zugesandt.
            </div>
            <a class="btn btn-warning mb-3 col-5" href="index.php<?php if (config::$MAINTENANCE) {
                echo "?m=0";
            } else if (config::$BETA) {
                echo "?b=0";
            } ?>">Zurück zum Login</a>
        </div>
    </div>
    <?php
} else if ($verhalten == 4) {
    ?>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card card-login col-5 align-items-center">
            <h3 class="mt-3 mb-3" style="color: white">Nutzername vergessen?</h3>
            <div class="text-light mb-3" style="text-align: center">
                Ihnen wurde Ihr Nutzername per Mail zugesandt.
            </div>
            <a class="btn btn-warning mb-3 col-5" href="index.php<?php if (config::$MAINTENANCE) {
                echo "?m=0";
            } else if (config::$BETA) {
                echo "?b=0";
            } ?>">Zurück zum Login</a>
        </div>
    </div>
    <?php
}
?>
</body>
</html>
</html>