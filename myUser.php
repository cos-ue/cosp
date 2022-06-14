<?php
/**
 * Page to change users data
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
checkLoginDeny($LOGIN);
checkPermission(config::$ROLE_UNAUTH_USER);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <?php
    $mailchanged = false;
    $checkUpdate = false;
    $firstnameChanged = false;
    $lastnameChanged = false;
    $GetData = array();
    if (count($_POST) > 0) {
        dump($_POST, 5);
        if (isset($_POST['email'], $_POST['firstname'], $_POST['lastname']) === false) {
            die("wrong keys in array");
        }
        $firstname = "";
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $lastname = "";
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $csrf = filter_input(INPUT_POST, "FormTokenScriptCSRF", FILTER_SANITIZE_STRING);
        if (!isset($_SESSION['csrf'])){
            Redirect('index.php');
        }
        if (!checkCSRFtoken($csrf))
        {
            Redirect('index.php');
        }
        $mailok = false;
        if ($email !== $_SESSION['email']) {
            if (checkMailAddress($email)) {
                $mailok = true;
                $mailchanged = true;
            }
        } else {
            $mailok = true;
        }
        $firstnameChanged = ($firstname != $_SESSION['firstname']);
        $lastnameChanged = ($lastname != $_SESSION['lastname']);
        if ($mailok) {
            $mailold = $_SESSION['email'];
            updateUser($firstname, $lastname, $email);
            if ($mailchanged) {
                updateMailValidated($_SESSION['name'], 0);
                $registrationCompletionLink = generateValidateableLink($_SESSION['name'], "validate.php");
                sendMail($email, "Änderung der Mailadresse des Nutzers " . $_SESSION['name'], MailTemplates::MailChangedNewAddress($registrationCompletionLink, $_SESSION['name']), true);
                sendMail($mailold, "Änderung der Mailadresse des Nutzers " . $_SESSION['name'], MailTemplates::MailChangedOldAddress($email, $_SESSION['name']), true);
                sendMail(config::$ZENTRAL_MAIL, "Änderung der Mailadresse des Nutzers " . $_SESSION['name'], MailTemplates::ZentralMailChanged($_SESSION['name']), true);
            }
            $newUserdata = getUserData($_SESSION['name']);
            if (isset($newUserdata)) {
                $checkUpdate = $firstname == $newUserdata['firstname'] && $lastname == $newUserdata['lastname'] && $email == $newUserdata['email'];
            }
            if($checkUpdate){
                $_SESSION['firstname'] = $firstname;
                $_SESSION['lastname'] = $lastname;
                $_SESSION['email'] = $email;
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

<body style="height: auto">
<?php
generateHeader(true);
?>
<div class="container h-75 text-light pt-3">
    <div class="offset-4">
        <div>
            <h1>Eigene Nutzerdaten verwalten</h1>
            <form action="myUser.php" method="post" name="wishes" class="col-6">
                <input type="text" value="<?php echo createCSRFtokenClient()?>" id="FormTokenScriptCSRF" name="FormTokenScriptCSRF" hidden>
                <label class="weiß2">Nutzername</label>
                <input class="form-control textinput" type="text" name="username" id="username_reg"
                       value="<?php echo $_SESSION['name'] ?>" disabled><br>

                <label class="weiß2">Vorname</label>
                <input class="form-control textinput <?php echo $checkUpdate && $firstnameChanged ? "border-success" : "" ?>" type="text" name="firstname"
                    <?php
                    echo 'value="' . $_SESSION['firstname'] . '" ';
                    ?>
                ><br>
                <label class="weiß2">Nachname</label>
                <input class="form-control textinput <?php echo $checkUpdate && $lastnameChanged ? "border-success" : "" ?>" type="text" name="lastname"
                    <?php
                    echo 'value="' . $_SESSION['lastname'] . '" ';
                    ?>
                ><br>

                <label class="weiß2">E-Mail-Adresse</label>
                <input class="form-control textinput <?php echo $checkUpdate && $mailchanged ? "border-success" : "" ?>" type="text" name="email"
                    <?php
                    echo 'value="' . $_SESSION['email'] . '" ';
                    ?>
                ><br>
                <?php
                if ($checkUpdate) {
                    ?>
                    <div class="mb-3 text-success">
                        Ihre Nutzerdaten wurden erfolgreich aktualisiert.
                        <?php
                        if ($mailchanged) {
                            ?>
                            Um sich weiterhin anmelden zu können, müssen Sie zuerst Ihre neue Mailaddresse verifizieren. Hierzu wurde Ihnen an die neue E-Mailadresse ein Verifizierungslink gesandt.
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
                <div>
                    <div class="float-left">
                        <a class="btn btn-success" style="color: black;" href="changePwd.php">Passwort ändern</a>
                    </div>
                    <div class="float-right">
                        <input class="btn btn-success" name="submitrequest" type="submit"
                               value="Ändern">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
