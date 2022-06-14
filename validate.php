<?php
/**
 * Page for validating email by link
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
$CheckValid = false;
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
                "seccode" => filter_input(INPUT_GET, 'seccode', FILTER_SANITIZE_STRING)
            );
            dump($GetData, 3);
            $CheckValid = checkValidatableLink($GetData);
            if ($CheckValid) {
                if (in_array($GetData['username'], getAllUsernames())) {
                    updateMailValidated($GetData['username'], true);
                } else {
                    $CheckValid = false;
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
generateHeader($LOGIN);
?>
<div class="container mx-auto pt-3">
    <div class="justify-content-center">
        <h1 class="weiß2">E-Mail Validation</h1>
        <?php
        if ($CheckValid === false) {
            ?>
            <h2 style="color: #ffffff">Validation fehlgeschlagen.</h2>
            <?php
        } else {
            ?>
            <h2 style="color: #ffffff">Ihre Mailadresse wurde erfolgreich validiert.</h2>
            <?php
        }
        ?>
    </div>
</div>
</body>
</html>
