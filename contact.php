<?php
/**
 * Page with contact form
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */

define('NICE_PROJECT', true);
require_once "bin/inc.php";
if (isset($_SESSION['name']) == false) {
    Redirect('index.php');
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kontakt</title>
    <?php
    generateHeaderTags(
        array(
            array(
                "type" => "script",
                "typeval" => "text/javascript",
                "href" => "js/loadCaptcha.js",
                "hrefmin" => "js/loadCaptcha.min.js"
            )
        )
    );
    ?>
</head>
<body style="height: auto">
<?php
generateHeader(isset($_SESSION['name']));
?>
<div class="container">
    <div class="mx-auto text-light pt-3 pb-3 mb-3">
        <h1>Kontaktformular</h1>
        <div class="hidden" id="contactSuccessMessage">
            <div class="text-success">
                Die Nachricht wurde erfolgreich versandt.
            </div>
        </div>
        <div id="captchaBox">
            <div class="mt-2">
                <img class="float-left captcha" id="Captcha"
                     style="width: 10em !important; height: calc(1.5em + .75rem + 2px) !important;"/>
                <input type="text" name="captchaReturn" id="captchaReturn"
                       class="form-control textinput float-left ml-2"
                       style="width: 10em !important;" required>
                <button class="btn btn-secondary ml-2" name="refresh" onclick="loadCaptchaContact()"
                        style="width: 10em !important;">Neues Captcha
                </button>
            </div>
        </div>
        <div id="contactFormular" class="mt-2">
            <div id="title">
                <form action="" method="post" name="contactFormular">
                    <label class="weiß2" for="ContactTitle">Betreff: </label>
                    <input type="text" name="ContactTitle" id="ContactTitle" class="form-control textinput" required>
                    <label class="weiß2 mt-2" for="ContactMessage">Nachricht: </label>
                    <textarea type="text" name="ContactMessage" id="ContactMessage" class="form-control textinput"
                              style="height: 15em !important;" required></textarea>
                </form>
                <button class="btn btn-important mt-2" name="sendMessage" onclick="submitContact()" style="width: 100%">
                    Absenden
                </button>
            </div>
        </div>
    </div>
</div>
</body>
</html>