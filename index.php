<?php
/**
 * frontpage of platform
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
$verhalten = 0;
if (config::$BETA) {
    $verhalten = 1;
}
if (isset($_GET['b'])) {
    $v = filter_input(INPUT_GET, 'b', FILTER_SANITIZE_STRING);
    if ($v == 0 && config::$BETA) {
        $verhalten = 0;
    }
}
if (config::$MAINTENANCE) {
    $verhalten = 5;
}
if (isset($_GET['m'])) {
    $v = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_STRING);
    if ($v == 0 && config::$MAINTENANCE) {
        $verhalten = 0;
    }
}
if (isset($_GET['f'])) {
    $f = filter_input(INPUT_GET, 'f', FILTER_SANITIZE_STRING);
    if ($f >= 0) {
        $verhalten = 6;
    }
}
$logout = false;
$username = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (count($_POST) > 0) {
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $csrf = filter_input(INPUT_POST, 'FormTokenScriptCSRF', FILTER_SANITIZE_STRING);
        if (!isset($_SESSION['csrf'])){
            Redirect('index.php', false);
        }
        if (!checkCSRFtoken($csrf)) {
            Redirect('index.php', false);
        }
        $result = checkPassword($password, $username);
        if ($result) {
            Redirect("hub.php", false);
        } else {
            $verhalten = 2;
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (count($_GET) > 0 && isset($_GET['logout'])) {
        $logout = filter_input(INPUT_GET, 'logout', FILTER_SANITIZE_STRING);
    }
    if (count($_GET) > 0 && isset($_GET['type'], $_GET['username'])) {
        $username_rest = filter_input(INPUT_GET, "username", FILTER_SANITIZE_STRING);
        $type_rest = filter_input(INPUT_GET, "type", FILTER_SANITIZE_STRING);
        if ($type_rest == "rup") {
            $userdata = getUserData($username_rest);
            PasswordResetViaMail($username_rest, $userdata['email']);
            $verhalten = 4;
        }
    }
}
if ($LOGIN && $logout === false) {
    Redirect("hub.php", false);
}
if ($logout) {
    $verhalten = 3;
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Anmelden</title>
    <?php
    generateHeaderTags(
        array(
            array(
                "type" => "script",
                "typeval" => "text/javascript",
                "href" => "js/loadCookie.js",
                "hrefmin" => "js/loadCookie.min.js"
            )
        )
    );
    ?>
</head>
<body>
<?php
generateHeader(false, true);
if ($verhalten == 0) {
    ?>
    <div class="container d-flex justify-content-center flex-column">
        <div class="d-flex align-items-center flex-column">
            <img class="mb-5" src="<?php echo config::$LOGO?>" style="height: 90px">
            <div class="card card-login col-5">
                <div class="card-body">
                    <span class="mb-2" style="font-size: 2rem; color: white; float: left">Anmeldung</span>
                    <form method="post" action="index.php">
                        <input type="text" value="<?php echo createCSRFtokenClient()?>" id="FormTokenScriptCSRF" name="FormTokenScriptCSRF" hidden>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 45px">
                                        <img src="images/user.svg" height="25px">
                                    </span>
                            </div>
                            <input type="text" name="username" class="form-control textinput"
                                   placeholder="Nutzername">

                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 45px">
                                        <img src="images/key.svg" height="25px" style="margin-left: -2px">
                                    </span>
                            </div>
                            <input type="password" name="password" class="form-control textinput"
                                   placeholder="Kennwort">
                        </div>
                        <div class="form-group d-flex">
                            <a href="registration.php" class="btn login-btn flex-fill mr-2">Registrieren</a>
                            <input type="submit" name="Einloggen" class="btn ml-2 login-btn btn-important flex-fill"
                                   value="Anmelden">
                        </div>
                        <div>
                            <a href="index.php?f=0" class="forgottenLink">Ich habe meine Anmeldedaten Vergessen.</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
} else if ($verhalten == 1) {
    ?>
    <div class="container d-flex align-items-center">
        <div class="d-flex justify-content-center">
            <div class="card card-login col-5">
                <div class="card-body">
                    <h3 class="text-light mb-3" style="text-align: left">Arbeit im Gange</h3>
                    <div class="d-flex justify-content-end text-light" style="text-align: left;">
                        Sehr geehrter Interessent, <br/>
                        Wir befinden uns momentan in einer geschlossen Testphase. Wir hoffen, Ihnen hier
                        demnächst eine fehlerfrei funktionierende Plattform bieten zu können.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else if ($verhalten == 2) {
    ?>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="card card-login col-5">
            <div class="card-body d-flex align-items-center flex-column">
                <h3 style="color: white">Anmeldung fehlgeschlagen</h3>
                <a class="btn btn-warning col-6 mt-2" href="index.php<?php if (config::$MAINTENANCE) {
                    echo "?m=0";
                } else if (config::$BETA) {
                    echo "?b=0";
                } ?>">Erneut versuchen</a>
                <?php
                if (in_array($username, getAllUsernames())) {
                    ?>
                    <a href="<?php echo "http://" . config::$DOMAIN . "/index.php?" . http_build_query(array("type" => "rup", "username" => $username)); ?>"
                       class="btn btn-warning col-6 mt-2 btn-important">Passwort ändern</a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
} else if ($verhalten == 3) {
    ?>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card card-login col-5 align-items-center">
            <h3 class="mt-3 mb-3" style="color: white">Ihre Abmeldung war erfolgreich.</h3>
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
} else if ($verhalten == 5) {
    ?>
    <div class="container d-flex align-items-center">
        <div class="d-flex justify-content-center">
            <div class="card card-login col-5">
                <div class="card-body">
                    <h3 class="text-light mb-3" style="text-align: left">Wartungsarbeiten</h3>
                    <div class="d-flex justify-content-start text-light" style="text-align: left;">
                        Sehr geehrter Nutzer, <br/>
                        Wir führen momentan Wartungsarbeiten durch, um die Plattform zu verbessern und sicherer
                        zu gestalten. Daher bitten wir Sie um etwas Geduld. Die Plattform wird in Kürze wieder
                        für Sie zur Verfügung stehen.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else if ($verhalten == 6) {
    ?>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="card card-login col-5">
            <div class="card-body d-flex align-items-center flex-column">
                <h3 style="color: white">Anmeldedaten vergessen</h3>
                <a href="forgottenLogin.php?n=1&e=0" class="btn btn-warning col mt-2">Ich kenne meinen Nutzernamen.</a>
                <a href="forgottenLogin.php?n=0&e=1" class="btn btn-warning col mt-2">Ich kenne meine E-Mailadresse.</a>
            </div>
        </div>
    </div>
    <?php
}
if ($logout == true) {
    session_destroy();
}
?>
<div class="modal fade col-6 offset-3" id="CookieBannerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered" role="document">
        <!-- because normal overflow-y: auto is displaying scrollbar next to modal and not on right side of browser window-->
        <div class="modal-content">
            <div class="modal-header" style="color: black">
                <h5 class="modal-title" style="color: white">Cookies und Datenschutz</h5>
            </div>
            <div class="modal-body modal-body-unround" style="" id="CookieModalBody">
                <div class="container">
                    <div class="text-light">
                        Diese Website nutzt Cookies, um die gewünschte Funktionalität zu erbringen und zu verbessern.
                        Durch Nutzung dieser Seite akzeptieren Sie Cookies. <a href="privacy-policy.php"
                                                                               class="text-light">Weitere
                            Informationen</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-important" id="CookieAcceptButton"
                        onclick="AcceptCookies();">
                    Akzeptieren
                </button>
            </div>
        </div>
    </div>
</div>
</body>
</html>